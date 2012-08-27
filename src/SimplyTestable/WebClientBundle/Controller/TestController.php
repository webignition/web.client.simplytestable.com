<?php

namespace SimplyTestable\WebClientBundle\Controller;

use webignition\NormalisedUrl\NormalisedUrl;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
    const DEFAULT_WEBSITE_SCHEME = 'http';
    
    public function startAction()
    {        
        if (!$this->hasWebsite()) {
            $this->get('session')->setFlash('test_start_error', 'non-blank string');
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
        $website = $this->getRequestValue('website');
        if (is_null($website)) {
            return $website;
        }
        
        $url = new NormalisedUrl($website);
        if (!$url->hasScheme()) {            
            $url->setScheme(self::DEFAULT_WEBSITE_SCHEME);
        }
        
        return (string)$url;
    }
    
    
    /**
     *
     * @return int 
     */
    private function getTestId() {
        return $this->getRequestValue('test_id', 0);
    }
    
    
    private function getRequestValue($name, $default = null) {
        $value = trim($this->get('request')->get($name));        
        return ($value == '') ? $default : $value;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */
    private function getTestService() {
        return $this->container->get('simplytestable.services.testservice');
    }
}