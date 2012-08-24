<?php
namespace SimplyTestable\WebClientBundle\Services;


class TaskService extends CoreApplicationService {    
    
    
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
    private $tests = array();
    
    
    /**
     * Collection of task statuses retrieved from core application
     * 
     * @var array
     */
    private $tasks = array();
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService
    ) {
        $this->parameters = $parameters;
        $this->webResourceService = $webResourceService;        
    }
    
    
    public function startTest($canonicalUrl) {
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
    public function hasTest($canonicalUrl, $testId) {
        return $this->getTest($canonicalUrl, $testId) instanceof \webignition\WebResource\JsonDocument\JsonDocument;
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return \webignition\WebResource\JsonDocument\JsonDocument|false|null 
     */
    public function getTest($canonicalUrl, $testId) {
        if (!isset($this->tests[$canonicalUrl])) {
            $this->retrieveTest($canonicalUrl, $testId);
        }
        
        if (!isset($this->tests[$canonicalUrl][$testId])) {
            $this->retrieveTest($canonicalUrl, $testId);
        }        
        
        return $this->tests[$canonicalUrl][$testId];
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @param int $testId
     * @return boolean
     */
    public function hasTask($canonicalUrl, $testId, $taskId) {
        return $this->getTaskStatus($canonicalUrl, $testId, $taskId) instanceof \webignition\WebResource\JsonDocument\JsonDocument;
    }    
    
   
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @param int $taskId
     * @return \webignition\WebResource\JsonDocument\JsonDocument|false|null 
     */
    public function getTask($canonicalUrl, $testId, $taskId) {
        if(!isset($this->tasks[$canonicalUrl])) {
            $this->retrieveTaskStatus($canonicalUrl, $testId, $taskId);
        }
        
        if(!isset($this->tasks[$canonicalUrl][$testId])) {
            $this->retrieveTaskStatus($canonicalUrl, $testId, $taskId);
        }        
        
        if(!isset($this->tasks[$canonicalUrl][$testId][$taskId])) {
            $this->retrieveTaskStatus($canonicalUrl, $testId, $taskId);
        }   
        
        return $this->tasks[$canonicalUrl][$testId][$taskId];        
    }
    
    
    public function getTestUrls($canonicalUrl, $testId) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_list_urls', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId
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
    
    
    private function retrieveTest($canonicalUrl, $testId) {
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
        
        $test = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $test =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $test = false;
            }
        }
        
        var_dump($test);
        exit();
        
        $this->tests[$canonicalUrl][$testId] = $test;        
    }
    
    
    private function retrieveTaskStatus($canonicalUrl, $testId, $taskId) {        
        if (!isset($this->tasks[$canonicalUrl])) {
            $this->tasks[$canonicalUrl] = array();
        }
        
        if (!isset($this->tasks[$canonicalUrl][$testId])) {
            $this->tasks[$canonicalUrl][$testId] = array();
        }
        
        if (isset($this->tasks[$canonicalUrl][$testId][$taskId])) {
            return;
        }      
        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('task_status', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId,
            'task_id' => $taskId
        )));
        
        $taskStatus = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $taskStatus =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $taskStatus = false;
            }
        }
        
        $this->tasks[$canonicalUrl][$testId][$taskId] = $taskStatus;         
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