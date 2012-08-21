<?php
namespace SimplyTestable\WebClientBundle\Services;


class CoreApplicationService {    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\WebResourceService 
     */
    private $webResourceService;
    
    
    /**
     *
     * @var array
     */
    private $parameters;
    
    /**
     * Collection of test statuses retrieved from core application
     *  
     * @var array
     */
    private $testStatus = array();
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService
    ) {
        $this->parameters = $parameters;
        $this->webResourceService = $webResourceService;        
    }
    
    
    public function testStart($canonicalUrl) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_start', array(
            'canonical-url' => $canonicalUrl
        )));
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            
        }
    }
    
    /**
     *
     * @param string $canonicalUrl
     * @param string $testId
     * @return boolean
     */
    public function hasTestStatus($canonicalUrl, $testId) {
        return $this->getTestStatus($canonicalUrl, $testId) instanceof \webignition\WebResource\JsonDocument\JsonDocument;
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param string $testId
     * @return \webignition\WebResource\JsonDocument\JsonDocument|false|null 
     */
    public function getTestStatus($canonicalUrl, $testId) {
        if (!isset($this->testStatus[$canonicalUrl])) {
            $this->retrieveTestStatus($canonicalUrl, $testId);
        }
        
        if (!isset($this->testStatus[$canonicalUrl][$testId])) {
            $this->retrieveTestStatus($canonicalUrl, $testId);
        }        
        
        return $this->testStatus[$canonicalUrl][$testId];
    }
    
    
    private function retrieveTestStatus($canonicalUrl, $testId) {
        if (!isset($this->testStatus[$canonicalUrl])) {
            $this->testStatus[$canonicalUrl] = array();
        }
        
        if (isset($this->testStatus[$canonicalUrl][$testId])) {
            return;
        }        
        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_status', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId
        )));
        
        $testStatus = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $testStatus =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $testStatus = false;
            }
        }
        
        $this->testStatus[$canonicalUrl][$testId] = $testStatus;        
    }    
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return boolean 
     */
    public function testCancel($canonicalUrl, $testId) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_cancel', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId
        )));
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $this->webResourceService->get($httpRequest);
            return true;
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                return false;
            }            
        }        
    }    
    
    
    private function getUrl($name, $parameters) {
        $url =  $this->parameters['urls']['base'] . $this->parameters['urls'][$name];
        
        if (is_array($parameters)) {
            foreach ($parameters as $parameterName => $parameterValue) {
                $url = str_replace('{'.$parameterName.'}', $parameterValue, $url);
            }
        }
        
        return $url;
    }
    
    
    private function getAuthorisedHttpRequest($url = '', $request_method = HTTP_METH_GET, $options = array()) {
        $httpRequest = new \HttpRequest($url, $request_method, $options);
        $httpRequest->addHeaders(array(
            'Authorization' => 'Basic ' . base64_encode('public:public')
        ));
        
        return $httpRequest;
    }
    
}