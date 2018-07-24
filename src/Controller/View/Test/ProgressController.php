<?php

namespace App\Controller\View\Test;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Services\CacheValidatorService;
use App\Services\Configuration\CssValidationTestConfiguration;
use App\Services\DefaultViewParameters;
use App\Services\Configuration\JsStaticAnalysisTestConfiguration;
use App\Services\RemoteTestService;
use App\Services\SystemUserService;
use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapterFactory as TestOptionsRequestAdapterFactory;
use App\Services\TestService;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;
use webignition\NormalisedUrl\NormalisedUrl;

class ProgressController extends AbstractRequiresValidOwnerController implements RequiresValidUser
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
            return $this->createRedirectResponse(
                $request,
                'view_test_progress',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ]
            );
        }

        if ($this->testService->isFinished($test)) {
            if (Test::STATE_FAILED_NO_SITEMAP  !== $test->getState() || SystemUserService::isPublicUser($user)) {
                return $this->createRedirectResponse(
                    $request,
                    'view_test_results',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ]
                );
            }

            return $this->createRedirectResponse(
                $request,
                'action_test_retest',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ]
            );
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
            $viewData = array_merge($commonViewData, [
                'remote_test' => $remoteTest->__toArray(),
                'this_url' => $this->generateUrl(
                    'view_test_progress',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
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

            $response = $this->renderWithDefaultViewParameters('test-progress.html.twig', $viewData, $response);
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
     * @param string $routeName
     * @param array $routeParameters
     *
     * @return RedirectResponse|JsonResponse
     */
    private function createRedirectResponse(Request $request, $routeName, array $routeParameters = [])
    {
        $requestHeaders = $request->headers;
        $requestedWithHeaderName = 'X-Requested-With';

        $isXmlHttpRequest = $requestHeaders->get($requestedWithHeaderName) == 'XMLHttpRequest';

        $urlReferenceType = $isXmlHttpRequest
            ? UrlGeneratorInterface::ABSOLUTE_URL
            : UrlGeneratorInterface::ABSOLUTE_PATH;

        $redirectUrl = $this->generateUrl($routeName, $routeParameters, $urlReferenceType);

        if ($isXmlHttpRequest) {
            return new JsonResponse([
                'this_url' => $redirectUrl
            ]);
        }

        $requestQuery = $request->query;

        if ($requestQuery->get('output') == 'json') {
            $normalisedUrl = new NormalisedUrl($redirectUrl);

            if ($normalisedUrl->hasQuery()) {
                $normalisedUrl->getQuery()->set('output', 'json');
            } else {
                $normalisedUrl->setQuery('output=json');
            }

            $redirectUrl = (string)$normalisedUrl;
        }

        return new RedirectResponse($redirectUrl);
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

        $acceptHeaders = AcceptHeader::fromString($request->headers->get('Accept'))->all();

        return key($acceptHeaders) === 'application/json';
    }
}
