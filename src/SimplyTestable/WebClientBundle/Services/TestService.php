<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Model\TestOptions;

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
    
    
    /**
     *
     * @var \stdClass
     */
    private $remoteTestSummary = null;
    
    
    /**
     *
     * @var Test
     */
    private $currentTest = null;
    
    
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
    
    
    public function persist(Test $test) {
        $this->entityManager->persist($test);
        $this->entityManager->flush();
    }
    
    
    public function start($canonicalUrl, TestOptions $testOptions, $testType = 'full site') {        
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_start', array(
            'canonical-url' => $canonicalUrl
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
     * @param int $limit
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     */
    public function getList($limit) {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('tests_list', array(
            'limit' => $limit
        )));
        
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
     * @param string $canonicalUrl
     * @param int $testId
     * @return boolean
     */
    public function has($canonicalUrl, $testId, User $user) {        
        if ($this->hasEntity($testId)) {
            return true;
        }       
        
        return $this->get($canonicalUrl, $testId, $user) instanceof Test;
    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return Test
     */
    public function get($canonicalUrl, $testId, User $user) {
        if ($this->hasEntity($testId)) {           
            /* @var $test Test */
            $this->currentTest = $this->fetchEntity($testId);          
            
            if (($this->currentTest->getState() != 'completed' && $this->currentTest->getState() != 'cancelled')) {
                $this->update();             
            }          
        } else {            
            $this->currentTest = new Test();
            $this->currentTest->setTestId($testId);
            $this->currentTest->setWebsite(new NormalisedUrl($canonicalUrl));            
            
            if (!$this->create()) {
                return false;
            }
        }        
        
        if ($this->currentTest->getUser() != $user->getUsername() && $this->currentTest->getUser() != 'public') {
            throw new UserServiceException(403);
        }
        
        $this->entityManager->persist($this->currentTest);
        $this->entityManager->flush();
        
        return $this->currentTest;
    }
    
    
    /**
     *
     * @param int $testId
     * @return boolean
     */
    private function hasEntity($testId) {        
        return $this->getEntityRepository()->hasByTestId($testId);
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
     * @return boolean
     */
    private function create() {
        try {
            $remoteTestSummary = $this->getRemoteTestSummary();  
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            if ($webResourceException->getResponse()->getStatusCode() === 403) {                
                throw new UserServiceException(403);
            }
            
            throw $webResourceException;
        }        
                    
        if (!$remoteTestSummary) {
            return false;
        }
        
        return $this->createTestFromRemoteTestSummary();
    }
    
    
    /**
     *
     * @return boolean
     */
    private function update() {        
        $remoteTestSummary = $this->getRemoteTestSummary();      
        if (!$remoteTestSummary) {
            return false;
        }
        
        $this->currentTest->setState($remoteTestSummary->state);
        $this->currentTest->setUrlCount($remoteTestSummary->url_count); 
        
        $this->updateTimePeriodFromJsonObject($this->currentTest->getTimePeriod(), $remoteTestSummary);       
    }
    
    
    /**
     *
     * @return \stdClass|boolean 
     */
    public function getRemoteTestSummary() {
        if (is_null($this->remoteTestSummary)) {
            $remoteTestSummaryJsonDocument = $this->retrieveRemoteTestSummary();
            if ($remoteTestSummaryJsonDocument instanceof \webignition\WebResource\JsonDocument\JsonDocument) {
                $this->remoteTestSummary = $remoteTestSummaryJsonDocument->getContentObject();
            } else {
                $this->remoteTestSummary = false;
            }
        }
        
        return $this->remoteTestSummary;
    }
    
    public function getLatestRemoteSummary($canonicalUrl) {
        $retrievalUrl = $this->getUrl('test_latest', array(
            'canonical-url' => $canonicalUrl
        ));
        
        $httpRequest = $this->getAuthorisedHttpRequest($retrievalUrl);
        
        $testJsonDocument = null;
        
        /* @var $testJsonDocument \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($httpRequest)->getContentObject();            
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
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     * @throws WebResourceException
     * @throws \Guzzle\Http\Exception\CurlException
     */
    private function retrieveRemoteTestSummary() {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_status', array(
            'canonical-url' => $this->currentTest->getWebsite(),
            'test_id' => $this->currentTest->getTestId()
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);   
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test 
     */
    private function createTestFromRemoteTestSummary() {        
        $remoteTestSummary = $this->getRemoteTestSummary();

        $this->currentTest->setState($remoteTestSummary->state);
        $this->currentTest->setUser($remoteTestSummary->user);
        $this->currentTest->setWebsite(new NormalisedUrl($remoteTestSummary->website));
        $this->currentTest->setTestId($remoteTestSummary->id);
        $this->currentTest->setUrlCount($remoteTestSummary->url_count);        
        $this->currentTest->setType($remoteTestSummary->type);
        
        $taskTypes = array();
        foreach ($remoteTestSummary->task_types as $taskTypeDetail) {
            $taskTypes[] = $taskTypeDetail->name;
        }  
        
        $this->currentTest->setTaskTypes($taskTypes);
        $this->updateTimePeriodFromJsonObject($this->currentTest->getTimePeriod(), $remoteTestSummary);             
        
        return true;
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
     * @param Test $test
     * @param int $testId
     * @return boolean 
     */
    public function cancel(Test $test) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_cancel', array(
            'canonical-url' => (string)$test->getWebsite(),
            'test_id' => $test->getTestId()
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest);       
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Repository\TestRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }
        
        return $this->entityRepository;
    } 
    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {        
        return $this->entityManager;
    }
    
    
}