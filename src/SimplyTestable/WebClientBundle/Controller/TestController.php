<?php

namespace SimplyTestable\WebClientBundle\Controller;

use webignition\IsHttpStatusCode\IsHttpStatusCode;

class TestController extends BaseController
{    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private $testQueueService;    
    
    
    public function queuedStatusAction($website) {        
        $normalisedWebsite = new \webignition\NormalisedUrl\NormalisedUrl($website);        
        $result = ($this->getTestQueueService()->contains($this->getUser(), (string)$normalisedWebsite)) ? 'queued' : 'not queued';
        
        return new \Symfony\Component\HttpFoundation\Response('"'.$result.'"');       
    }
    
    
    public function cancelQueuedAction($website) {        
        $normalisedWebsite = new \webignition\NormalisedUrl\NormalisedUrl($website);        
        if ($this->getTestQueueService()->contains($this->getUser(), (string)$normalisedWebsite)) {
            $this->getTestQueueService()->dequeue($this->getUser(), (string)$normalisedWebsite);
            $this->get('session')->setFlash('test_cancelled_queued_website', (string)$normalisedWebsite);
        }        
        
        return $this->redirect($this->generateUrl('app', array(), true));
    }
    
    
    public function cancelAction()
    {        
        $this->getTestService()->setUser($this->getUser());
        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', '');
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        $cancelResult = $this->getTestService()->cancel($this->getWebsite(), $this->getTestId());
        
        if ($cancelResult === true) {
            return $this->redirect($this->generateUrl(
                'app_results',
                array(
                    'website' => $this->getWebsite(),
                    'test_id' => $this->getTestId()
                ),
                true
            ));       
        }        
        
        if (IsHttpStatusCode::check($cancelResult)) {
            $this->get('session')->setFlash('test_cancel_error', $cancelResult);

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
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    protected function getTestQueueService() {
        if (is_null($this->testQueueService)) {
            $this->testQueueService = $this->container->get('simplytestable.services.testqueueservice');
            $this->testQueueService->setApplicationRootDirectory($this->container->get('kernel')->getRootDir());
                    
        }
        
        return $this->testQueueService;

    }     
}