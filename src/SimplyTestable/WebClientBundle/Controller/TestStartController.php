<?php

namespace SimplyTestable\WebClientBundle\Controller;

use SimplyTestable\WebClientBundle\Model\TestOptions;

class TestStartController extends BaseController
{ 
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private $testQueueService;
    
    
    private $allowedTestTypeMap = array(
        'html-validation' => 'HTML validation',
        'css-validation' => 'CSS validation'
    );
    
    
    public function startAction()
    {        
        $this->getTestService()->setUser($this->getUser());
        
        $this->getTestOptionsRequestParserService()->setRequestData($this->getRequestValues(HTTP_METH_POST));
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
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 503) {
                $this->getTestQueueService()->enqueue($this->getUser(), $this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'));
                return $this->redirect($this->generateUrl(
                    'app_latest',
                    array(
                        'website' => $this->getTestUrl()
                    ),
                    true
                ));                
            }
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
        $requestParameters = $this->getRequestValues(HTTP_METH_POST);
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
     * @param string $name
     * @return boolean
     */
    private function isOptionsSelected($name) {
        return $this->getRequestValue($name) === "1";
    }    
    
    
    /**
     * 
     * @return array|boolean
     */
    private function getTestOptions() {
        $testTypes = $this->getTestTypes();
        
        if (count($testTypes) == 0) {
            return false;
        }
        
        return array(
            'test-types' => $testTypes
        );
    }
    
    
    /**
     * 
     * @return array
     */
    private function getTestTypes() {        
        $testTypes = array();
        
        foreach ($this->allowedTestTypeMap as $testTypeKey => $testTypeName) {
            if ($this->getRequestValue($testTypeKey) === "1") {
                $testTypes[$testTypeKey] = $testTypeName;
            }
        }              
        
        return $testTypes;
    }
    
    
    
    
    
    public function cancelAction()
    {
        $this->getTestService()->setUser($this->getUser());
        
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
        return trim($this->getRequestValue('website')) != '';
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
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestOptions\RequestParserService
     */
    private function getTestOptionsRequestParserService() {
        return $this->container->get('simplytestable.services.testoptions.requestparserservice');
    }    
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private function getTestQueueService() {
        if (is_null($this->testQueueService)) {
            $this->testQueueService = $this->container->get('simplytestable.services.testqueueservice');
            $this->testQueueService->setApplicationRootDirectory($this->container->get('kernel')->getRootDir());
                    
        }
        
        return $this->testQueueService;

    }    
}