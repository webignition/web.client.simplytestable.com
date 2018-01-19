<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Progress;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexController extends CacheableViewController implements IEFiltered, RequiresValidUser, RequiresValidOwner
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
     * @throws WebResourceException
     */
    public function indexAction(Request $request, $website, $test_id)
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');
        $cacheableResponseService = $this->container->get('simplytestable.services.cacheableresponseservice');
        $router = $this->container->get('router');
        $urlViewValuesService = $this->container->get('simplytestable.services.urlviewvalues');
        $serializer = $this->container->get('serializer');
        $taskTypeService = $this->container->get('simplytestable.services.tasktypeservice');
        $testOptionsAdapterFactory = $this->container->get('simplytestable.services.testoptions.adapter.factory');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

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

            return $this->issueRedirect($redirectUrl, $request);
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

                return $this->issueRedirect($redirectUrl, $request);
            }

            $redirectUrl = $router->generate(
                'app_test_retest',
                [
                    'website' => $testWebsite,
                    'test_id' => $test_id
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return $this->issueRedirect($redirectUrl, $request);
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
            $viewData = array_merge($commonViewData, [
                'remote_test' => $remoteTest->__toArray(),
                'this_url' => $this->getProgressUrl($testWebsite, $test_id),
            ]);

            $response = new Response($serializer->serialize($viewData, 'json'));
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

            $response = parent::render(
                'SimplyTestableWebClientBundle:bs3/Test/Progress/Index:index.html.twig',
                array_merge($this->getDefaultViewParameters(), $viewData)
            );
        }

        return $cacheableResponseService->getCachableResponse($request, $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheValidatorParameters()
    {
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $userService = $this->container->get('simplytestable.services.userservice');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $request = $this->getRequest();
        $requestAttributes = $request->attributes;

        $website = $requestAttributes->get('website');
        $testId = $requestAttributes->get('test_id');

        $test = $testService->get($website, $testId);
        $remoteTest = $remoteTestService->get();

        return [
            'website' => $website,
            'test_id' => $testId,
            'is_public' => $remoteTest->getIsPublic(),
            'is_public_user_test' => $test->getUser() == $userService->getPublicUser()->getUsername(),
            'timestamp' => ($request->query->has('timestamp')) ? $request->query->get('timestamp') : '',
            'state' => $test->getState()
        ];
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
            $label = $this->getRemoteTest()->getCompletionPercent() . '% done';
        }

        if (in_array($testState, [Test::STATE_QUEUED, Test::STATE_IN_PROGRESS])) {
            $label = $remoteTest->getUrlCount() . ' urls, ' . $remoteTest->getTaskCount() . ' tests; ' . $label;
        }

        if ($testState == Test::STATE_CRAWLING) {
            $remoteTestCrawl = $remoteTest->getCrawl();

            $label .= sprintf(
                ': %s pages examined, %s of %s found',
                $remoteTestCrawl->processed_url_count,
                $remoteTestCrawl->discovered_url_count,
                $remoteTestCrawl->limit
            );
        }

        return $label;
    }
}
