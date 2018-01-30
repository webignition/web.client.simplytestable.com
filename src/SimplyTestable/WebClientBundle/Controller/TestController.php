<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Guzzle\Http\Exception\CurlException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Services\TestService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use webignition\WebResource\JsonDocument\JsonDocument;

class TestController extends BaseController
{
    const ACTION_LOCK = 'lock';
    const ACTION_UNLOCK = 'unlock';

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function lockAction($website, $test_id)
    {
        return $this->lockUnlock($website, $test_id, self::ACTION_LOCK);
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function unlockAction($website, $test_id)
    {
        return $this->lockUnlock($website, $test_id, self::ACTION_UNLOCK);
    }

    /**
     * @param string $website
     * @param int $test_id
     * @param string $action
     *
     * @return RedirectResponse
     */
    private function lockUnlock($website, $test_id, $action)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $router = $this->container->get('router');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        try {
            $testService->get($website, $test_id);

            if (self::ACTION_LOCK === $action) {
                $remoteTestService->lock();
            } else {
                $remoteTestService->unlock();
            }

        } catch (\Exception $e) {
            // We already redirect back to test results regardless of if this action succeeds
        }

        $redirectUrl = $router->generate(
            'view_test_results_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function cancelAction($website, $test_id)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $router = $this->container->get('router');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        $routeParameters = [
            'website' => $website,
            'test_id' => $test_id,
        ];

        try {
            $testService->get($website, $test_id);
            $remoteTestService->cancel();
        } catch (WebResourceException $webResourceException) {
            if ($webResourceException->getResponse()->getStatusCode() === 403) {
                $redirectUrl = $router->generate(
                    'view_dashboard_index_index',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                return new RedirectResponse($redirectUrl);
            }

            $redirectUrl = $router->generate(
                'view_test_progress_index_index',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        } catch (CurlException $curlException) {
            $redirectUrl = $router->generate(
                'view_test_progress_index_index',
                $routeParameters,
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            return new RedirectResponse($redirectUrl);
        }

        $redirectUrl = $router->generate(
            'view_test_results_index_index',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param string $website
     * @param int $test_id
     *
     * @return RedirectResponse
     */
    public function cancelCrawlAction($website, $test_id)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $testService = $this->container->get('simplytestable.services.testservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $router = $this->container->get('router');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        try {
            $test = $testService->get($website, $test_id);
            $remoteTest = $remoteTestService->get();

            $remoteTestService->cancelByTestProperties($remoteTest->getCrawl()->id, $test->getWebsite());
        } catch (WebResourceException $webResourceException) {
            // Nothing happens, we redirect to the test progress page regardless
        } catch (CurlException $curlException) {
            // Nothing happens, we redirect to the test progress page regardless
        }

        $redirectUrl = $router->generate(
            'view_test_progress_index_index',
            [
                'website' => $website,
                'test_id' => $test_id,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @param string $website
     * @param string $test_id
     *
     * @return RedirectResponse
     *
     * @throws WebResourceException
     */
    public function retestAction($website, $test_id)
    {
        $userService = $this->container->get('simplytestable.services.userservice');
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');
        $router = $this->container->get('router');

        $user = $userService->getUser();
        $remoteTestService->setUser($user);

        /* @var JsonDocument $response */
        $response = $remoteTestService->retest($test_id, $website);

        $responseData = $response->getContentArray();

        $redirectUrl = $router->generate(
            'view_test_progress_index_index',
            [
                'website' => $responseData['website'],
                'test_id' => $responseData['id'],
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($redirectUrl);
    }

    /**
     * @return bool
     */
    protected function hasWebsite()
    {
        return trim($this->getRequestValue('website')) != '';
    }

    /**
     * @return string
     */
    protected function getWebsite()
    {
        $websiteUrl = $this->getNormalisedRequestUrl();
        if (!$websiteUrl) {
            return null;
        }

        return (string)$websiteUrl;
    }

    /**
     * @return TestService
     */
    protected function getTestService()
    {
        return $this->container->get('simplytestable.services.testservice');
    }
}
