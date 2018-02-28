<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Progress;

use Negotiation\FormatNegotiator;
use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\Configuration\CssValidationTestConfiguration;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\Configuration\JsStaticAnalysisTestConfiguration;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\TaskTypeService;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use SimplyTestable\WebClientBundle\Services\TestService;
use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;

class IndexController extends AbstractRequiresValidOwnerController implements RequiresValidUser
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var TaskTypeService
     */
    private $taskTypeService;

    /**
     * @var TestOptionsRequestAdapterFactory
     */
    private $testOptionsRequestAdapterFactory;

    /**
     * @var CssValidationTestConfiguration
     */
    private $cssValidationTestConfiguration;

    /**
     * @var JsStaticAnalysisTestConfiguration
     */
    private $jsStaticAnalysisTestConfiguration;

    /**
     * @var string[]
     */
    private $testStateLabelMap = array(
        'new' => 'New, waiting to start',
        'queued' => 'waiting for first test to begin',
        'resolving' => 'Resolving website',
        'resolved' => 'Resolving website',
        'preparing' => 'Finding URLs to test: looking for sitemap or news feed',
        'crawling' => 'Finding URLs to test',
        'failed-no-sitemap' => 'Finding URLs to test: preparing to crawl'
    );

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     * @param SessionInterface $session
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param TaskTypeService $taskTypeService
     * @param TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory
     * @param CssValidationTestConfiguration $cssValidationTestConfiguration
     * @param JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        SessionInterface $session,
        TestService $testService,
        RemoteTestService $remoteTestService,
        TaskTypeService $taskTypeService,
        TestOptionsRequestAdapterFactory $testOptionsRequestAdapterFactory,
        CssValidationTestConfiguration $cssValidationTestConfiguration,
        JsStaticAnalysisTestConfiguration $jsStaticAnalysisTestConfiguration
    ) {
        parent::__construct(
            $router,
            $twig,
            $defaultViewParameters,
            $cacheValidator,
            $urlViewValues,
            $userManager,
            $session
        );

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->taskTypeService = $taskTypeService;
        $this->testOptionsRequestAdapterFactory = $testOptionsRequestAdapterFactory;
        $this->cssValidationTestConfiguration = $cssValidationTestConfiguration;
        $this->jsStaticAnalysisTestConfiguration = $jsStaticAnalysisTestConfiguration;
    }

    /**
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $user = $this->userManager->getUser();

        $test = $this->testService->get($website, $test_id);
        $remoteTest = $this->remoteTestService->get();

        $testWebsite = (string)$test->getWebsite();

        if ($testWebsite !== $website) {
            $redirectUrl = $this->router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->createRedirectResponse($request, $redirectUrl);
        }

        if ($this->testService->isFinished($test)) {
            if (Test::STATE_FAILED_NO_SITEMAP  !== $test->getState() || SystemUserService::isPublicUser($user)) {
                $redirectUrl = $this->router->generate(
                    'view_test_results_index_index',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                return $this->createRedirectResponse($request, $redirectUrl);
            }

            $redirectUrl = $this->router->generate(
                'app_test_retest',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->createRedirectResponse($request, $redirectUrl);
        }

        $requestTimeStamp = $request->query->get('timestamp');
        $isPublicUserTest = $test->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheValidator->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $isPublicUserTest,
            'timestamp' => empty($requestTimeStamp) ? '' : $requestTimeStamp,
            'state' => $test->getState()
        ]);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $this->taskTypeService->setUser($user);
        if (!SystemUserService::isPublicUser($user)) {
            $this->taskTypeService->setUserIsAuthenticated();
        }

        $testOptionsAdapter = $this->testOptionsRequestAdapterFactory->create();
        $testOptionsAdapter->setRequestData($remoteTest->getOptions());
        $testOptionsAdapter->setInvertInvertableOptions(true);

        $commonViewData = [
            'test' => $test,
            'state_label' => $this->getStateLabel($test, $remoteTest),
        ];

        if ($this->requestIsForApplicationJson($request)) {
            $testProgressUrl = $this->router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $viewData = array_merge($commonViewData, [
                'remote_test' => $remoteTest->__toArray(),
                'this_url' => $testProgressUrl,
            ]);

            $response->setContent(json_encode($viewData));
            $response->headers->set('content-type', 'application/json');
        } else {
            $viewData = array_merge($commonViewData, [
                'remote_test' => $remoteTest,
                'website' => $this->urlViewValues->create($testWebsite),
                'available_task_types' => $this->taskTypeService->getAvailable(),
                'task_types' => $this->taskTypeService->get(),
                'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
                'is_public_user_test' => $isPublicUserTest,
                'css_validation_ignore_common_cdns' =>
                    $this->cssValidationTestConfiguration->getExcludedDomains(),
                'js_static_analysis_ignore_common_cdns' =>
                    $this->jsStaticAnalysisTestConfiguration->getExcludedDomains(),
                'default_css_validation_options' => [
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
                ],
                'default_js_static_analysis_options' => [
                    'ignore-common-cdns' => 1,
                    'jslint-option-maxerr' => 50,
                    'jslint-option-indent' => 4,
                    'jslint-option-maxlen' => 256
                ],
            ]);

            $response = $this->renderWithDefaultViewParameters(
                'SimplyTestableWebClientBundle:bs3/Test/Progress/Index:index.html.twig',
                $viewData,
                $response
            );
        }

        return $response;
    }

    /**
     * @param Test $test
     * @param RemoteTest $remoteTest
     *
     * @return string
     */
    private function getStateLabel(Test $test, RemoteTest $remoteTest)
    {
        $testState = $test->getState();

        $label = (isset($this->testStateLabelMap[$testState]))
            ? $this->testStateLabelMap[$testState]
            : '';

        if ($testState == Test::STATE_IN_PROGRESS) {
            $label = $remoteTest->getCompletionPercent() . '% done';
        }

        if (in_array($testState, [Test::STATE_QUEUED, Test::STATE_IN_PROGRESS])) {
            $label = $remoteTest->getUrlCount() . ' urls, ' . $remoteTest->getTaskCount() . ' tests; ' . $label;
        }

        if ($testState == Test::STATE_CRAWLING) {
            $remoteTestCrawl = $remoteTest->getCrawl();

            $label .= sprintf(
                ': %s pages examined, %s of %s found',
                $remoteTestCrawl['processed_url_count'],
                $remoteTestCrawl['discovered_url_count'],
                $remoteTestCrawl['limit']
            );
        }

        return $label;
    }

    /**
     * @param Request $request
     * @param string $locationValue
     *
     * @return RedirectResponse|JsonResponse
     */
    private function createRedirectResponse(Request $request, $locationValue)
    {
        $requestHeaders = $request->headers;
        $requestedWithHeaderName = 'X-Requested-With';

        $isXmlHttpRequest = $requestHeaders->get($requestedWithHeaderName) == 'XMLHttpRequest';

        if ($isXmlHttpRequest) {
            return new JsonResponse([
                'this_url' => $locationValue
            ]);
        }

        $requestQuery = $request->query;

        if ($requestQuery->get('output') == 'json') {
            $normalisedUrl = new NormalisedUrl($locationValue);

            if ($normalisedUrl->hasQuery()) {
                $normalisedUrl->getQuery()->set('output', 'json');
            } else {
                $normalisedUrl->setQuery('output=json');
            }

            $locationValue = (string)$normalisedUrl;
        }

        return new RedirectResponse($locationValue);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function requestIsForApplicationJson(Request $request)
    {
        if (!$request->headers->has('accept')) {
            return false;
        }

        $negotiator = new FormatNegotiator();
        $priorities = array('*/*');
        $format = $negotiator->getBest($request->headers->get('accept'), $priorities);

        return $format->getValue() == 'application/json';
    }
}
