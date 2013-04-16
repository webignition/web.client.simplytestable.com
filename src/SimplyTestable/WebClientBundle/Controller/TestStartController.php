<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestOptions;

class TestStartController extends TestController
{
    
    public function startAction()
    {        
        $this->getTestService()->setUser($this->getUser());
        
        $this->getTestOptionsRequestParserService()->setRequestData($this->getRequestValues(\Guzzle\Http\Message\Request::POST));
        $testOptions = $this->getTestOptionsRequestParserService()->getTestOptions();
        
        if (!$this->hasWebsite()) {            
            $this->get('session')->setFlash('test_start_error', 'website-blank');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        }
        
        if ($this->getWebsiteBlockListService()->contains($this->getWebsite())) {
            $this->get('session')->setFlash('test_start_error', 'website-blocked');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));            
        }
        
        if ($testOptions->hasTestTypes() === false) {
            $this->get('session')->setFlash('test_start_error', 'no-test-types-selected');
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));                
        }
        
        try {
            $jsonResponseObject = $this->getTestService()->start($this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'))->getContentObject();
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $jsonResponseObject->website,
                    'test_id' => $jsonResponseObject->id
                ),
                true
            ));
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->get('session')->setFlash('test_start_error', 'curl-error');
            $this->get('session')->setFlash('curl_error_code', $curlException->getErrorNo());
            return $this->redirect($this->generateUrl('app', $this->getRedirectValues($testOptions), true));
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {                            
            $this->getTestQueueService()->enqueue($this->getUser(), $this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'), $webResourceException->getResponse()->getStatusCode());
            return $this->redirect($this->generateUrl(
                'app_website',
                array(
                    'website' => $this->getTestUrl()                        
                ),
                true
            ));
        }
    }
    
    
    /**
     * 
     * @return type
     */
    private function getTestUrl() {
        if ($this->isFullTest()) {
            return $this->getCanonicalUrlFromWebsite($this->getWebsite());
        }
        
        $url = new \webignition\NormalisedUrl\NormalisedUrl($this->getWebsite());
        return (string)$url;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function isFullTest() {
        /* @var $requestParameters \Symfony\Component\HttpFoundation\ParameterBag */
        $requestParameters = $this->getRequestValues(\Guzzle\Http\Message\Request::POST);
        if (!$requestParameters->has('full-single')) {
            return true;
        }
        
        return $requestParameters->get('full-single') == 'full';
    }
    
    
    /**
     * 
     * @param string $website
     * @return string
     */
    private function getCanonicalUrlFromWebsite($website) {
        $url = new \webignition\NormalisedUrl\NormalisedUrl($website);
        $url->setFragment(null);
        $url->setPath('/');
        $url->setQuery(null);
        
        return (string)$url;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\TestOptions $testOptions
     * @return array
     */
    private function getRedirectValues(TestOptions $testOptions) {
        $redirectValues = array();
        
        if ($this->hasWebsite()) {
            $redirectValues['website'] = trim($this->getRequestValue('website'));
        }
        
        $absoluteTestTypes = $testOptions->getAbsoluteTestTypes();        
        foreach ($absoluteTestTypes as $testTypeKey => $selectedValue) {
            $redirectValues[$testTypeKey] = $selectedValue;
            $redirectValues = array_merge($redirectValues, $testOptions->getAbsoluteTestTypeOptions($testTypeKey));
        }
        
        return $redirectValues;
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
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    }  
}