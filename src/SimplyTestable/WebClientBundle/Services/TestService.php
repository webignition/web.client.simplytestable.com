<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;

use webignition\NormalisedUrl\NormalisedUrl;


class TestService extends CoreApplicationService {        
    
    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\Test\Test';      
    
    /**
     *
     * @var Logger
     */
    private $logger;     
    
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
     *
     * @var \SimplyTestable\WebClientBundle\Services\TaskOutputService
     */
    private $taskOutputService;
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TaskService
     */
    private $taskService;    
    
    
    public function __construct(
        EntityManager $entityManager,
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutputService $taskOutputService,
        Logger $logger,
        \SimplyTestable\WebClientBundle\Services\TaskService $taskService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->entityManager = $entityManager; 
        $this->taskOutputService = $taskOutputService;
        $this->logger = $logger;
        $this->taskService = $taskService;
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
    
    
    public function getList($limit) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('tests_list', array(
            'limit' => $limit
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
        if ($this->hasEntity($testId)) {
            return true;
        }
        
        return $this->get($canonicalUrl, $testId) instanceof Test;
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @param array $taskIds get updated only for specified tasks
     * @return Test
     */
    public function get($canonicalUrl, $testId, $taskIds = null) {        
        if ($this->hasEntity($testId)) {
            /* @var $test Test */
            $test = $this->fetchEntity($testId);
            
            if ($test->getState() != 'completed' && $test->getState() != 'cancelled') {
                $this->update($test, $taskIds);             
            }
        } else {
            $test = new Test();
            $test->setTestId($testId);
            $test->setWebsite(new NormalisedUrl($canonicalUrl));            
            $test = $this->create($test);
        }
        
        $this->entityManager->persist($test);
        $this->entityManager->flush();
        
        return $test;
    }
    
    
    /**
     *
     * @param int $testId
     * @return boolean
     */
    private function hasEntity($testId) {
        return !is_null($this->fetchEntity($testId));
    }
    
    
    /**
     *
     * @param int $testId
     * @return type 
     */
    private function fetchEntity($testId) {
        return $this->getEntityRepository()->findOneBy(array(
            'testId' => $testId
        ));
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
            'test_id' => $test->getTestId()
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
    
    
    /**
     *
     * @param Test $test
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test|false
     */
    private function create(Test $test) {
        $testJsonDocument = $this->retrieve($test);
        if (!$testJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
            return false;
        }
        
        return $this->createTestFromJsonObject($testJsonDocument->getContentObject());
    }
    
    
    /**
     *
     * @param Test $test
     * @param array $taskIds limit update to specified tasks only
     * @return boolean|null 
     */
    private function update(Test $test, $taskIds = null) {        
        $testJsonDocument = $this->retrieve($test, $taskIds);
        if (!$testJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
            return false;
        }
        
        $jsonObject = $testJsonDocument->getContentObject();
        
        $test->setState($jsonObject->state);
        $test->setUrlCount($jsonObject->url_total);
        $this->updateTimePeriodFromJsonObject($test->getTimePeriod(), $jsonObject);
        
        foreach ($jsonObject->tasks as $taskJsonObject) {
            $retrievedTask = $this->createTaskFromJsonObject($taskJsonObject);
            
            if ($test->hasTask($retrievedTask)) {
                $currentTask = $test->getTask($retrievedTask);
                if ($currentTask->getState() != $retrievedTask->getState()) {                    
                    $this->populateTaskFromJsonObject($currentTask, $taskJsonObject);
                    $this->entityManager->persist($currentTask);
                }
            } else {
                $retrievedTask->setTest($test);
                $test->addTask($retrievedTask);
                $this->entityManager->persist($retrievedTask);
            }
        }        
    }
    
    
    
    /**
     *
     * @param Test $test
     * @param array $taskIds limit update to specified tasks only
     * @return \webignition\WebResource\JsonDocument\JsonDocument 
     */
    private function retrieve(Test $test, $taskIds = null) {        
        $retrievalUrl = $this->getUrl('test_status', array(
            'canonical-url' => $test->getWebsite(),
            'test_id' => $test->getTestId()
        ));
        
        if (is_array($taskIds)) {
            $retrievalUrl .= '?taskIds='.  implode(',', $this->taskService->getRemoteTaskIds($taskIds));            
        }
        
        $httpRequest = $this->getAuthorisedHttpRequest($retrievalUrl);
        
        $testJsonDocument = null;
        
        /* @var $testJsonDocument \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            $testJsonDocument =  $this->webResourceService->get($httpRequest);
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                $testJsonDocument = false;
            }
        }
        
        return $testJsonDocument;     
    }
    
    
    /**
     *
     * @param \stdClass $testJsonObject
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test 
     */
    private function createTestFromJsonObject($testJsonObject) {
        $test = new Test();        
        $test->setState($testJsonObject->state);                
        $test->setUrlCount($testJsonObject->url_total);
        $test->setUser($testJsonObject->user);
        $test->setWebsite(new NormalisedUrl($testJsonObject->website));
        $test->setTestId($testJsonObject->id);
        
        foreach ($testJsonObject->tasks as $taskJsonObject) {
            $task = $this->createTaskFromJsonObject($taskJsonObject);
            $task->setTest($test);                    
            $test->addTask($task);
        }
        
        $taskTypes = array();
        foreach ($testJsonObject->task_types as $taskTypeDetail) {
            $taskTypes[] = $taskTypeDetail->name;
        }
        
        $test->setTaskTypes($taskTypes);
        $this->updateTimePeriodFromJsonObject($test->getTimePeriod(), $testJsonObject);        
        
        return $test;        
    }
    
    
    
    /**
     *
     * @param \stdClass $jsonObject
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
     */
    private function createTaskFromJsonObject($jsonObject) {        
        return $this->populateTaskFromJsonObject(new Task(), $jsonObject);
    }
    
    
    /**
     *
     * @param Task $task
     * @param \stdClass $jsonObject
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
     */
    private function populateTaskFromJsonObject(Task $task, $jsonObject) {        
        $task->setTaskId($jsonObject->id);
        $task->setUrl($jsonObject->url);
        $task->setState($jsonObject->state);        
        $task->setType($jsonObject->type);
        $this->updateTimePeriodFromJsonObject($task->getTimePeriod(), $jsonObject);       
        
        if (isset($jsonObject->worker)) {
            $task->setWorker($jsonObject->worker);
        } 
        
        return $task;        
    }
    
    
    /**
     *
     * @param TimePeriod $timePeriod
     * @param type $jsonObject
     * @return \SimplyTestable\WebClientBundle\Entity\TimePeriod 
     */
    private function updateTimePeriodFromJsonObject(TimePeriod $timePeriod, $jsonObject) {
        if (isset($jsonObject->time_period)) {
            if (isset($jsonObject->time_period->start_date_time)) {
                $timePeriod->setStartDateTime(new \DateTime($jsonObject->time_period->start_date_time));
            }

            if (isset($jsonObject->time_period->end_date_time)) {
                $timePeriod->setEndDateTime(new \DateTime($jsonObject->time_period->end_date_time));
            }       
        }
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return boolean 
     */
    public function cancel($canonicalUrl, $testId) {
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
            $this->logger->warn('TestService::cancel:curlException: ['.$curlException->getCode().'] ['.$curlException->getMessage().']');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            $this->logger->warn('TestService::cancel:WebResourceServiceException: ['.$webResourceServiceException->getCode().'] ['.$webResourceServiceException->getMessage().']');
            if ($webResourceServiceException->getCode() == 403) {
                return false;
            }            
        }        
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