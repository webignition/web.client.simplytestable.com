<?php

namespace SimplyTestable\WebClientBundle\Controller;

use webignition\NormalisedUrl\NormalisedUrl;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends BaseController
{    
    public function startAction()
    {        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', 'non-blank string');
            return $this->redirect($this->generateUrl('app', array(), true));
        }        
        
        if ($this->getWebsiteBlockListService()->contains($this->getWebsite())) {
            $this->get('session')->setFlash('test_start_error_blocked_website', 'non-blank string');
            $this->get('session')->setFlash('website', $this->getWebsite());
            return $this->redirect($this->generateUrl('app', array(), true));            
        }
        
        $jsonResponseObject = $this->getTestService()->start($this->getWebsite())->getContentObject();        
        return $this->redirect($this->generateUrl(
            'app_progress',
            array(
                'website' => $jsonResponseObject->website,
                'test_id' => $jsonResponseObject->id
            ),
            true
        ));
    }
    
    
    public function cancelAction()
    {
        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', '');
            return $this->redirect($this->generateUrl('app', array(), true));
        }
        
        if ($this->getTestService()->cancel($this->getWebsite(), $this->getTestId())) {
            return $this->redirect($this->generateUrl(
                'app_results',
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
    private function hasWebsite() {
        return $this->getWebsite() != '';
    }
    
    
    /**
     *
     * @return string
     */
    private function getWebsite() {
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
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\WebsiteBlockListService
     */
    private function getWebsiteBlockListService() {
        $websiteBlockListService = $this->get('simplytestable.services.websiteblocklistservice');
        $websiteBlockListService->setBlockListResourcePath($this->container->get('kernel')->locateResource('@SimplyTestableWebClientBundle/Resources/config/WebsiteBlockList.txt'));
        
        return $websiteBlockListService;
    }    
}