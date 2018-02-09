<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Progress;

use Negotiation\FormatNegotiator;
use SimplyTestable\WebClientBundle\Controller\View\Test\AbstractRequiresValidOwnerController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\NormalisedUrl\NormalisedUrl;

class IndexController extends AbstractRequiresValidOwnerController implements IEFiltered, RequiresValidUser
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

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
     * @param Request $request
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse|Response
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $router = $this->container->get('router');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $testOptionsAdapterFactory = $this->container->get('simplytestable.services.testoptions.adapter.factory');
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $templating = $this->container->get('templating');

        $user = $userService->getUser();

        $test = $testService->get($website, $test_id);
        $remoteTest = $remoteTestService->get();

        $testWebsite = (string)$test->getWebsite();

        if ($testWebsite !== $website) {
            $redirectUrl = $router->generate(
                'view_test_progress_index_index',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->issueRedirect($request, $redirectUrl);
        }

        if ($testService->isFinished($test)) {
            if ($test->getState() !== Test::STATE_FAILED_NO_SITEMAP || $userService->isPublicUser($user)) {
                $redirectUrl = $router->generate(
                    'view_test_results_index_index',
                    [
                        'website' => $testWebsite,
                        'test_id' => $test_id
                    ],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                return $this->issueRedirect($request, $redirectUrl);
            }

            $redirectUrl = $router->generate(
                'app_test_retest',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->issueRedirect($request, $redirectUrl);
        }

        $requestTimeStamp = $request->query->get('timestamp');

        $response = $cacheValidatorService->createResponse($request, [
            'website' => $website,
            'test_id' => $test_id,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
            'timestamp' => empty($requestTimeStamp) ? '' : $requestTimeStamp,
            'state' => $test->getState()
        ]);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $taskTypeService->setUser($user);

        $isAuthenticated = !$userService->isPublicUser($user);

        if ($isAuthenticated) {
            $taskTypeService->setUserIsAuthenticated();
        }

        $testOptionsAdapter = $testOptionsAdapterFactory->create();
        $testOptionsAdapter->setRequestData($remoteTest->getOptions());
        $testOptionsAdapter->setInvertInvertableOptions(true);

        $commonViewData = [
            'test' => $test,
            'state_label' => $this->getStateLabel($test, $remoteTest),
        ];

        if ($this->requestIsForApplicationJson($request)) {
            $testProgressUrl = $router->generate(
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
                'website' => $urlViewValuesService->create($testWebsite),
                'available_task_types' => $taskTypeService->getAvailable(),
                'task_types' => $taskTypeService->get(),
                'test_options' => $testOptionsAdapter->getTestOptions()->__toKeyArray(),
                'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
                'css_validation_ignore_common_cdns' => $this->container->getParameter(
                    'css-validation-ignore-common-cdns'
                ),
                'js_static_analysis_ignore_common_cdns' => $this->container->getParameter(
                    'js-static-analysis-ignore-common-cdns'
                ),
                'default_css_validation_options' => array(
                    'ignore-warnings' => 1,
                    'vendor-extensions' => 'warn',
                    'ignore-common-cdns' => 1
                ),
                'default_js_static_analysis_options' => array(
                    'ignore-common-cdns' => 1,
                    'jslint-option-maxerr' => 50,
                    'jslint-option-indent' => 4,
                    'jslint-option-maxlen' => 256
                ),
            ]);

            $content = $templating->render(
                'SimplyTestableWebClientBundle:bs3/Test/Progress/Index:index.html.twig',
                array_merge($this->getDefaultViewParameters(), $viewData)
            );

            $response->setContent($content);
            $response->headers->set('content-type', 'text/html');
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
    private function issueRedirect(Request $request, $locationValue)
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

        return parent::redirect($locationValue);
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
