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
        $website = trim($this->get('request')->request->get('website'));
        if ($website == '') {
            return $website;
        }
        
        $url = new NormalisedUrl($website);
        if (!$url->hasScheme()) {            
            $url->setScheme(self::DEFAULT_WEBSITE_SCHEME);
        }
        
        return (string)$url;
    }
}