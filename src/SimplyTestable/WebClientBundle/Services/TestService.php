<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Test\Test;


class TestService extends CoreApplicationService {    
    
    /**
     * Collection of test statuses retrieved from core application
     *  
     * @var array
     */
    private $tests = array();
    
    
    /**
     * @var \SimplyTestable\WebClientBundle\Services\Test\Deserializer
     */
    private $testDeserializer;
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\Test\Deserializer $testDeserializer
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->testDeserializer = $testDeserializer;     
    }     
    
    
    public function start($canonicalUrl) {
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
     * @param int $testId
     * @return boolean
     */
    public function has($canonicalUrl, $testId) {
        return $this->get($canonicalUrl, $testId) instanceof Test;
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return Test
     */
    public function get($canonicalUrl, $testId) {
        if (!isset($this->tests[$canonicalUrl])) {
            $this->retrieve($canonicalUrl, $testId);
        }
        
        if (!isset($this->tests[$canonicalUrl][$testId])) {
            $this->retrieve($canonicalUrl, $testId);
        }
        
        return $this->tests[$canonicalUrl][$testId];
    }
    
    /**
     *
     * @param Test $test
     * @return array|false 
     */
    public function getUrls(Test $test) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_list_urls', array(
            'canonical-url' => (string)$test->getWebsite(),
            'test_id' => $test->getId()
        )));
        
        $testUrls = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $testUrls = $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $testUrls = false;
            }
        }
        
        return $testUrls;
    }
    
    
    private function retrieve($canonicalUrl, $testId) {
        if (!isset($this->tests[$canonicalUrl])) {
            $this->tests[$canonicalUrl] = array();
        }
        
        if (isset($this->tests[$canonicalUrl][$testId])) {
            return;
        }        
        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_status', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId
        )));
        
        $testJsonDocument = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $testJsonDocument =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $testJsonDocument = false;
            }
        }
        
        $this->tests[$canonicalUrl][$testId] = $this->testDeserializer->deserialize($testJsonDocument->getContentObject());      
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
    
}