<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
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
     * @var \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory
     */
    private $taskOutputResultParserService;  
    
    
    
    public function __construct(
        EntityManager $entityManager,
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory $taskOutputResultParserService
    ) {
        parent::__construct($parameters, $webResourceService);  
        $this->entityManager = $entityManager;
        $this->taskOutputResultParserService = $taskOutputResultParserService;        
    }
    
    
    /**
     *
     * @param Test $test
     * @param Task $task
     * @return boolean
     */
    public function has(Test $test, Task $task) {
        if ($this->hasEntity($task)) {
            return true;
        }
        
        return $this->get($test, $task) instanceof Output;
    } 
    
    
    /**
     *
     * @return boolean
     */
    private function hasEntity(Task $task) {
        return !is_null($this->fetch($task));
    }
    
   
    /**
     *
     * @param Test $test
     * @param Task $task
     * @return SimplyTestable\WebClientBundle\Entity\Task\Output
     */
    public function get(Test $test, Task $task) {        
        if ($task->hasOutput()) {
            $taskOutput = $this->fetch($task);
        } else {
            $taskOutput = $this->retrieve($test, $task);
            $this->entityManager->persist($taskOutput);
            $this->entityManager->flush($taskOutput);
        }
        
        return $taskOutput;      
    } 
    
    
    /**
     *
     * @param Test $test
     * @param array $tasks
     * @return array 
     */
    public function getCollection(Test $test, $tasks) {         
        $taskOutputCollection = $this->retrieveCollection($test, $tasks);
        
        foreach ($tasks as $task) {
            /* @var $task Task */            
            $this->entityManager->persist($taskOutputCollection[$task->getTaskId()]);
            
        }
        
        $this->entityManager->flush();
        return $taskOutputCollection;        
    }
    
    
    /**
     *
     * @param Task $task
     * @return type 
     */
    private function fetch(Task $task) {
        return $this->getEntityRepository()->findOneBy(array(
            'task' => $task
        ));
    }
    
    

    /**
     *
     * @param Test $test
     * @param Task $task
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Output
     */
    private function retrieve(Test $test, Task $task) {        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('task_status', array(
            'canonical-url' => (string)$test->getWebsite(),
            'test_id' => $test->getTestId(),
            'task_id' => $task->getTaskId()
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
        
        $taskOutput = new Output();
        $taskOutput->setContent($taskOutputResponse->getContentObject()->output->output);
        $taskOutput->setType($taskOutputResponse->getContentObject()->type);       
        
        $task->setOutput($taskOutput);
        
        return $taskOutput;
    }
    
    
    private function retrieveCollection(Test $test, $tasks) {
        $tasksById = array();

        foreach ($tasks as $task) {
            /* @var $task Task */
            if ($task->getTest()->getId() == $test->getId() && !$task->hasOutput()) {
                $tasksById[$task->getTaskId()] = $task;
            }
        }
        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('task_collection_status', array(
            'canonical-url' => (string)$test->getWebsite(),
            'test_id' => $test->getTestId()            
        )));
        
        $httpRequest->setMethod(\Guzzle\Http\Message\Request::POST);
        
        $httpRequest->setPostFields(array(
            'task_ids' => implode(',', array_keys($tasksById))            
        ));
        
        $taskOutputCollectionResponse = null;
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $taskOutputCollectionResponse =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $taskOutputCollectionResponse = false;
            }
        }
        
        $responseContentObject = $taskOutputCollectionResponse->getContentObject();
        $taskOutputCollection = array();
        
        foreach ($responseContentObject as $taskOutputResponseObject) {
            $taskOutput = new Output();
            $taskOutput->setContent($taskOutputResponseObject->output->output);
            $taskOutput->setType($taskOutputResponseObject->type);             
            $taskOutputCollection[$taskOutputResponseObject->id] = $taskOutput;
            $tasksById[$taskOutputResponseObject->id]->setOutput($taskOutput);
        }
        
        return $taskOutputCollection;        
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
    
    
    /**
     *
     * @param Task $task 
     * @return Task
     */
    public function setParsedOutput(Task $task) {         
        if ($task->hasOutput()) {            
            $parser = $this->taskOutputResultParserService->getParser($task->getOutput());            
            $parser->setOutput($task->getOutput());

            $task->getOutput()->setResult($parser->getResult());
        }
        
        return $task;
    }
    
}