<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

class TaskOutputService extends CoreApplicationService {    
    
    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\Task\Output';    
    
    
    /**
     *
     * @var EntityManager 
     */
    private $entityManager;
    
    
    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $entityRepository;    
    
    
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
        EntityManager $entityManager,
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer\FactoryService $taskOutputDeserializerFactoryService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->taskOutputDeserializerFactoryService = $taskOutputDeserializerFactoryService;     
        $this->entityManager = $entityManager;
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
     * @return SimplyTestable\WebClientBundle\Entity\Task\Output
     */
    public function get($canonicalUrl, $testId, $taskId) {
        if ($this->hasEntity($taskId)) {
            $taskOutput = $this->fetchEntity($taskId);
        } else {
            $taskOutput = $this->retrieve($canonicalUrl, $testId, $taskId);
            $this->entityManager->persist($taskOutput);
            $this->entityManager->flush($taskOutput);
        }
        
        return $taskOutput;      
    }
    
    
    /**
     *
     * @param int $taskId
     * @return boolean
     */
    private function hasEntity($taskId) {
        return !is_null($this->fetchEntity($taskId));
    }
    
    
    
    /**
     *
     * @param int $taskId
     * @return type 
     */
    private function fetchEntity($taskId) {
        return $this->getEntityRepository()->findOneBy(array(
            'taskId' => $taskId
        ));
    }
    
    

    /**
     *
     * @param string $canonicalUrl
     * @param integer $testId
     * @param integer $taskId
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Output
     */
    private function retrieve($canonicalUrl, $testId, $taskId) {        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('task_status', array(
            'canonical-url' => $canonicalUrl,
            'test_id' => $testId,
            'task_id' => $taskId
        )));
        
        $taskOutputResponse = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $taskOutputResponse =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $taskOutputResponse = false;
            }
        }
        
        
        
        
        //$task = new 
        
        
        var_dump($taskOutputResponse->getContentObject()->state);
        exit();
        
        $taskOutput = new Output();
        $taskOutput->setContent($taskOutputResponse->getContentObject()->output->output);
        $taskOutput->setTaskId($taskId);
        $taskOutput->setType($taskOutputResponse->getContentObject()->type);
        
        
        return $taskOutput;
        
//        $deserializer = $this->taskOutputDeserializerFactoryService->getDeserializer(
//            $taskStatus->getContentObject()->type,
//            $taskStatus->getContentObject()->output->content_type
//        );
        
        
        
        //return $deserializer->deserialize($taskStatus->getContentObject()->output->output);       
    }
    
    
    
    /**
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }
        
        return $this->entityRepository;
    }      
    
}