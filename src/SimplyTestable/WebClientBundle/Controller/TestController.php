<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\IsHttpStatusCode\IsHttpStatusCode;

class TestController extends BaseController
{
    
    public function lockAction() {
        return $this->lockUnlock('lock');        
    }
    
    public function unlockAction() {
        return $this->lockUnlock('unlock');
    } 
    
    
    private function lockUnlock($action) {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        try {
            if ($this->getTestService()->has($this->getWebsite(), $this->getTestId())) {
                $test = $this->getTestService()->get($this->getWebsite(), $this->getTestId()); 
                
                if ($this->getTestService()->getRemoteTestService()->authenticate()) {           
                    $this->getTestService()->getRemoteTestService()->$action($test);
                }                 
            }                        
        } catch (\Exception $e) {            
            // We already redirect back to test results regardless of if this action succeeds
        }
        
        return $this->redirect($this->generateUrl(
            'app_results',
            array(
                'website' => $this->getWebsite(),
                'test_id' => $this->getTestId()
            ),
            true
        ));        
    }
    
    
    public function cancelAction()
    {
        $this->getTestService()->getRemoteTestService()->setUser($this->getUser());
        
        try {
            if (!$this->getTestService()->has($this->getWebsite(), $this->getTestId())) {
                return $this->redirect($this->generateUrl(
                    'app',
                    array(),
                    true
                ));
            }            
            
            $test = $this->getTestService()->get($this->getWebsite(), $this->getTestId());            
            if (!$this->getTestService()->getRemoteTestService()->authenticate()) {           
                return $this->redirect($this->generateUrl(
                    'app',
                    array(),
                    true
                ));
            }
            
            $this->getTestService()->getRemoteTestService()->cancel();
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $test->getWebsite(),
                    'test_id' => $test->getTestId()
                ),
                true
            ));
           
        } catch (WebResourceException $webResourceException) {            
            if ($webResourceException->getResponse()->getStatusCode() == 403) {
                return $this->redirect($this->generateUrl(
                    'app',
                    array(),
                    true
                ));                
            }
            
            $this->getLogger()->err('TestController::cancelAction:webResourceException ['.$webResourceException->getResponse()->getStatusCode().']');            
            
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            )); 
        } catch (\Guzzle\Http\Exception\CurlException $curlException)  {
            $this->getLogger()->err('TestController::cancelAction:curlException ['.$curlException->getErrorNo().']');
            
            return $this->redirect($this->generateUrl(
                'app_progress',
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
                'app_progress',
                array(
                    'website' => $test->getWebsite(),
                    'test_id' => $test->getTestId()
                ),
                true
            ));           
        } catch (\SimplyTestable\WebClientBundle\Exception\UserServiceException $userServiceException) {
            return $this->redirect($this->generateUrl(
                'app',
                array(),
                true
            ));
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {                        
            $this->getLogger()->err('TestController::cancelAction:webResourceException ['.$webResourceException->getResponse()->getStatusCode().']');            
            
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));             

        } catch (\Guzzle\Http\Exception\CurlException $curlException)  {
            $this->getLogger()->err('TestController::cancelAction:curlException ['.$curlException->getErrorNo().']');
            
            return $this->redirect($this->generateUrl(
                'app_progress',
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
            'app_progress',
            array(
                'website' => $response->getContentObject()->website,
                'test_id' => $response->getContentObject()->id
            ),
            true
        ));      
    }     
}