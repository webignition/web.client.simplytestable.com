<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\TestOptions;

class RemoteTestService extends CoreApplicationService {
    
    /**
     *
     * @var RemoteTest
     */
    private $remoteTest = null;
    
    
    /**
     *
     * @var Test
     */
    private $test;
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     */
    public function setTest(Test $test) {        
        $this->test = $test;
        
        if ($this->get()->getId() != $test->getTestId()) {
            $this->remoteTest = null;
        }
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test
     */
    public function getTest() {
        return $this->test;
    }
    
    
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site') {                
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_start', array(
            'canonical-url' => rawurlencode($canonicalUrl)
        )).'?'.http_build_query(array_merge(array(
            'type' => $testType
        ), $testOptions->__toArray())));

        $this->addAuthorisationToRequest($httpRequest);

        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($httpRequest);
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            throw $curlException;
        }
    }    
    
    
    
    /**
     * 
     * @return boolean
     */
    public function authenticate() {
        if ($this->owns()) {
            return true;
        }
        
        return $this->isPublic();
    }     
    
    
    /**
     * 
     * @return boolean
     * @throws \SimplyTestable\WebClientBundle\Services\WebResourceException
     */
    public function owns() {
        if ($this->getUser()->getUsername() == $this->getTest()->getUser()) {
            return true;
        }
        
        try {
            return $this->getUser()->getUsername() == $this->get()->getUser();         
        } catch (WebResourceException $webResourceException) {            
            if ($webResourceException->getCode() == 403) {
                return false;
            }
            
            throw $webResourceException;
        }
    } 
    
    
    /**
     * 
     * @return boolean
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     * @throws \SimplyTestable\WebClientBundle\Services\CurlException
     */
    public function isPublic() {        
        return $this->get()->getIsPublic();
    }
    
    
    
    /**
     *
     * @return \stdClass|boolean 
     */
    public function get() {        
        if (is_null($this->remoteTest)) {            
            $remoteJsonDocument = $this->retrieve();
            
            if ($remoteJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                $this->remoteTest = new RemoteTest($remoteJsonDocument->getContentObject());
            } else {
                $this->remoteTest = false;
            }
        }
        
        return $this->remoteTest;
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function has() {
        if (is_null($this->remoteTest)) {
            return false;
        }
        
        if (is_null($this->test)) {
            return false;
        }
        
        return $this->get()->getId() == $this->getTest()->getTestId();
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest $remoteTest
     */
    public function set(RemoteTest $remoteTest) {
        $this->remoteTest = $remoteTest;
    }
    
    
    /**
     * 
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     * @throws WebResourceException
     * @throws \Guzzle\Http\Exception\CurlException
     */
    private function retrieve() {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_status', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);   
    } 
    
    
    /**
     *
     * @param Test $test
     * @param int $testId
     * @return boolean 
     */
    public function cancel() {        
        return $this->cancelByTestProperties($this->getTest()->getTestId(), $this->getTest()->getWebsite());      
    }
    

    /**
     * 
     * @param int $testId
     * @param string $website
     * @return boolean
     */
    public function cancelByTestProperties($testId, $website) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_cancel', array(
            'canonical-url' => urlencode($website),
            'test_id' => $testId
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);          
    }     
    
    
    /**
     * 
     * @param int $testId
     * @param string $website
     * @return boolean
     */
    public function retest($testId, $website) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_retest', array(
            'canonical-url' => urlencode($website),
            'test_id' => $testId
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);          
    }    
    
    
    
    public function lock() {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_set_private', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);       
        return true;
    }
    
    
    public function unlock() {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_set_public', array(
            'canonical-url' => urlencode($this->getTest()->getWebsite()),
            'test_id' => $this->getTest()->getTestId()
        )));
        
        $this->addAuthorisationToRequest($request);
        $this->webResourceService->get($request);       
        return true;        
    } 
    
    
    public function getCurrent() {
        $requestUrl = $this->getUrl('tests_current');   
        
        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
        
        $this->addAuthorisationToRequest($request);
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($request);
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            
        }          
    }  
    
    
    /**
     * 
     * @param int $limit
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     */
    public function getList($limit, $excludeTypes = null, $excludeStates = null) {
        $requestUrl = $this->getUrl('tests_list', array(
            'limit' => $limit
        ));
        
        $queryParts = array();               
        
        if (is_array($excludeTypes)) {
            foreach ($excludeTypes as $excludeType) {
                $queryParts[] = 'exclude-types[]=' . $excludeType;
            }
        }
        
        if (is_array($excludeStates)) {
            foreach ($excludeStates as $excludeState) {
                $queryParts[] = 'exclude-states[]=' . $excludeState;
            }
        }
        
        $queryParts[] = 'exclude-current=1';
        
        $requestUrl .= '?' . implode('&', $queryParts);        
        
        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
        
        $this->addAuthorisationToRequest($request);
        
        $tests = array();
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $responseDocument = $this->webResourceService->get($request);
            foreach ($responseDocument->getContentObject() as $remoteTestData) {
                $tests[] = new RemoteTest($remoteTestData);
            }
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
        }        
        
        return $tests;        
    }    
    
    
    
    public function retrieveLatest($canonicalUrl) {                
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_latest', array(
            'canonical-url' => urlencode($canonicalUrl)
        )));
        
        $this->addAuthorisationToRequest($request);        
        
        /* @var $testJsonDocument \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $remoteJsonDocument = $this->webResourceService->get($request);           
            
            if ($remoteJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                return new RemoteTest($remoteJsonDocument->getContentObject());
            }
            
            return false;
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return null;
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            if ($webResourceException->getCode() == 403) {
                return false;
            }
        }
        
        return null;          
    }    
    
}