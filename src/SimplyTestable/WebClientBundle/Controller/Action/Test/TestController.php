<?php

namespace SimplyTestable\WebClientBundle\Controller\Action\Test;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class TestController
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
     * @var RouterInterface
     */
    private $router;

    /**
     * @param TestService $testService
     * @param RemoteTestService $remoteTestService
     * @param RouterInterface $router
     */
    public function __construct(
        TestService $testService,
        RemoteTestService $remoteTestService,
        RouterInterface $router
    ) {
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

        return new RedirectResponse($this->router->generate(
            'view_test_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
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

        return new RedirectResponse($this->router->generate(
            'view_test_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
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
            return new RedirectResponse($this->router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return new RedirectResponse($this->router->generate(
                'view_test_progress_index_index',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        return new RedirectResponse($this->router->generate(
            'view_test_results_index_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
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

        return new RedirectResponse($this->router->generate(
            'view_test_progress_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
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

        return new RedirectResponse($this->router->generate(
            'view_test_progress_index_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $remoteTest->getId(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
