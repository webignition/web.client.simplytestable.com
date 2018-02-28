<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test;

use SimplyTestable\WebClientBundle\Controller\AbstractController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class TestController extends AbstractController
{
    /**
     * @var TestService
     */
    private $testService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @param RouterInterface $router
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        RouterInterface $router,
        TestService $testService,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router);

        $this->testService = $testService;
        $this->remoteTestService = $remoteTestService;
        $this->router = $router;
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function lockAction($website, $test_id)
    {
        try {
            $this->testService->get($website, $test_id);
            $this->remoteTestService->lock();
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ]
        ));
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function unlockAction($website, $test_id)
    {
        try {
            $this->testService->get($website, $test_id);
            $this->remoteTestService->unlock();
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ]
        ));
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     */
    public function cancelAction($website, $test_id)
    {
        $routeParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        try {
            $this->testService->get($website, $test_id);
            $this->remoteTestService->cancel();
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return new RedirectResponse($this->generateUrl('view_dashboard_index_index'));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return new RedirectResponse($this->generateUrl(
                'view_test_progress_index_index',
                $routeParameters
            ));
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_results_index_index',
            $routeParameters
        ));
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     */
    public function cancelCrawlAction($website, $test_id)
    {
        try {
            $test = $this->testService->get($website, $test_id);
            $remoteTest = $this->remoteTestService->get();
            $crawlData = $remoteTest->getCrawl();

            $this->remoteTestService->cancelByTestProperties($crawlData['id'], $test->getWebsite());
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Nothing happens, we redirect to the test progress page regardless
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Nothing happens, we redirect to the test progress page regardless
        }

        return new RedirectResponse($this->generateUrl(
            'view_test_progress_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ]
        ));
    }

    /**
     * @param string $website
     * @param string $test_id
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function retestAction($website, $test_id)
    {
        $remoteTest = $this->remoteTestService->retest($test_id, $website);

        return new RedirectResponse($this->generateUrl(
            'view_test_progress_index_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $remoteTest->getId(),
            ]
        ));
    }
}
