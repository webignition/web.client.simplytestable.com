<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TestController extends Controller
{
    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function lockAction($website, $test_id)
    {
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $router = $this->container->get('router');

        try {
            $testService->get($website, $test_id);
            $remoteTestService->lock();
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new RedirectResponse($router->generate(
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
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $router = $this->container->get('router');

        try {
            $testService->get($website, $test_id);
            $remoteTestService->unlock();
        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        return new RedirectResponse($router->generate(
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
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $router = $this->container->get('router');

        $routeParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        try {
            $testService->get($website, $test_id);
            $remoteTestService->cancel();
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return new RedirectResponse($router->generate(
                'view_dashboard_index_index',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            return new RedirectResponse($router->generate(
                'view_test_progress_index_index',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            ));
        }

        return new RedirectResponse($router->generate(
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
        $testService = $this->container->get(TestService::class);
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $router = $this->container->get('router');

        try {
            $test = $testService->get($website, $test_id);
            $remoteTest = $remoteTestService->get();
            $crawlData = $remoteTest->getCrawl();

            $remoteTestService->cancelByTestProperties($crawlData['id'], $test->getWebsite());
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Nothing happens, we redirect to the test progress page regardless
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Nothing happens, we redirect to the test progress page regardless
        }

        return new RedirectResponse($router->generate(
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
        $remoteTestService = $this->container->get(RemoteTestService::class);
        $router = $this->container->get('router');

        $remoteTest = $remoteTestService->retest($test_id, $website);

        return new RedirectResponse($router->generate(
            'view_test_progress_index_index',
            [
                'website' => $remoteTest->getWebsite(),
                'test_id' => $remoteTest->getId(),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        ));
    }
}
