<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\Task\Task;
use SimplyTestable\WebClientBundle\Model\Task\Output;

class TaskOutputService extends CoreApplicationService {    
    
    
    /**
     * Collection of test outputs retrieved from core application
     *  
     * @var array
     */
    private $output = array();
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer\FactoryService 
     */
    private $taskOutputDeserializerFactoryService;
    
    
    
    public function __construct(
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer\FactoryService $taskOutputDeserializerFactoryService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->taskOutputDeserializerFactoryService = $taskOutputDeserializerFactoryService;     
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @param int $taskId
     * @return boolean
     */
    public function has($canonicalUrl, $testId, $taskId) {
        return $this->get($canonicalUrl, $testId, $taskId) instanceof Output;
    }    
    
   
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @param int $taskId
     * @return SimplyTestable\WebClientBundle\Model\Task\Output
     */
    public function get($canonicalUrl, $testId, $taskId) {
        if(!isset($this->output[$canonicalUrl])) {
            $this->retrieve($canonicalUrl, $testId, $taskId);
        }
        
        if(!isset($this->output[$canonicalUrl][$testId])) {
            $this->retrieve($canonicalUrl, $testId, $taskId);
        }        
        
        if(!isset($this->output[$canonicalUrl][$testId][$taskId])) {
            $this->retrieve($canonicalUrl, $testId, $taskId);
        }   
        
        return $this->output[$canonicalUrl][$testId][$taskId];        
    }
    
    

    
    private function retrieve($canonicalUrl, $testId, $taskId) {        
        if (!isset($this->output[$canonicalUrl])) {
            $this->output[$canonicalUrl] = array();
        }
        
        if (!isset($this->output[$canonicalUrl][$testId])) {
            $this->output[$canonicalUrl][$testId] = array();
        }
        
        if (isset($this->output[$canonicalUrl][$testId][$taskId])) {
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
        
        $deserializer = $this->taskOutputDeserializerFactoryService->getDeserializer(
            $taskStatus->getContentObject()->type,
            $taskStatus->getContentObject()->output->content_type
        );
        
        $this->output[$canonicalUrl][$testId][$taskId] = $deserializer->deserialize($taskStatus->getContentObject()->output->output);       
    }
    
}