<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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


    public function cancelAction()
    {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());

        try {
            if (!$this->getTestService()->has($this->getWebsite(), $this->getTestId())) {
                return $this->redirect($this->generateUrl(
                    'view_dashboard_index_index',
                    array(),
                    true
                ));
            }

            $test = $this->getTestService()->get($this->getWebsite(), $this->getTestId());
            if (!$this->getTestService()->getRemoteTestService()->authenticate()) {
                return $this->redirect($this->generateUrl(
                    'view_dashboard_index_index',
                    array(),
                    true
                ));
            }

            $this->getTestService()->getRemoteTestService()->cancel();
            return $this->redirect($this->generateUrl(
                'view_test_results_index_index',
                array(
                    'website' => $test->getWebsite(),
                    'test_id' => $test->getTestId()
                ),
                true
            ));

        } catch (WebResourceException $webResourceException) {
            if ($webResourceException->getResponse()->getStatusCode() == 403) {
                return $this->redirect($this->generateUrl(
                    'view_dashboard_index_index',
                    array(),
                    true
                ));
            }

            $this->getLogger()->err('TestController::cancelAction:webResourceException ['.$webResourceException->getResponse()->getStatusCode().']');

            return $this->redirect($this->generateUrl(
                'view_test_progress_index_index',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));
        } catch (\Guzzle\Http\Exception\CurlException $curlException)  {
            $this->getLogger()->err('TestController::cancelAction:curlException ['.$curlException->getErrorNo().']');

            return $this->redirect($this->generateUrl(
                'view_test_progress_index_index',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));
        }
    }


    public function cancelCrawlAction() {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());

        try {
            $test = $this->getTestService()->get($this->getWebsite(), $this->getTestId(), $this->getUser());
            $remoteTest = $this->getTestService()->getRemoteTestService()->get();

            $this->getTestService()->getRemoteTestService()->cancelByTestProperties($remoteTest->getCrawl()->id, $test->getWebsite());
            return $this->redirect($this->generateUrl(
                'view_test_progress_index_index',
                array(
                    'website' => $test->getWebsite(),
                    'test_id' => $test->getTestId()
                ),
                true
            ));
        } catch (\SimplyTestable\WebClientBundle\Exception\UserServiceException $userServiceException) {
            return $this->redirect($this->generateUrl(
                'view_dashboard_index_index',
                array(),
                true
            ));
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->getLogger()->err('TestController::cancelAction:webResourceException ['.$webResourceException->getResponse()->getStatusCode().']');

            return $this->redirect($this->generateUrl(
                'view_test_progress_index_index',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));

        } catch (\Guzzle\Http\Exception\CurlException $curlException)  {
            $this->getLogger()->err('TestController::cancelAction:curlException ['.$curlException->getErrorNo().']');

            return $this->redirect($this->generateUrl(
                'view_test_progress_index_index',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));
        }
    }


    /**
     *
     * @return boolean
     */
    protected function hasWebsite() {
        return trim($this->getRequestValue('website')) != '';
    }


    /**
     *
     * @return string
     */
    protected function getWebsite() {
        $websiteUrl = $this->getNormalisedRequestUrl();
        if (!$websiteUrl) {
            return null;
        }

        return (string)$websiteUrl;
    }


    /**
     *
     * @return int
     */
    private function getTestId() {
        return $this->getRequestValue('test_id', 0);
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    protected function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }

    public function retestAction($website, $test_id) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        $response = $this->getTestService()->getRemoteTestService()->retest($test_id, $website);

        return $this->redirect($this->generateUrl(
            'view_test_progress_index_index',
            array(
                'website' => $response->getContentObject()->website,
                'test_id' => $response->getContentObject()->id
            ),
            true
        ));
    }
}