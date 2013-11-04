<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest;

use webignition\NormalisedUrl\NormalisedUrl;


class TestService {    
    
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
     * @var \SimplyTestable\WebClientBundle\Services\TaskService
     */
    private $taskService;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\RemoteTestService
     */
    private $remoteTestService;
    
    
//    /**
//     *
//     * @var \stdClass
//     */
//    private $remoteTestSummary = null;
    
    
    /**
     *
     * @var Test
     */
    private $test = null;
    
//    /**
//     *
//     * @var boolean
//     */
//    private $currentTestIsPublic = null;
    
    
    public function __construct(
        EntityManager $entityManager,
        Logger $logger,
        \SimplyTestable\WebClientBundle\Services\TaskService $taskService,
        \SimplyTestable\WebClientBundle\Services\RemoteTestService $remoteTestService            
    ) {
        $this->entityManager = $entityManager; 
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->remoteTestService = $remoteTestService;
    }
    
    
    /**
     * 
     * @return Test
     */
    private function getTest() {
        return $this->test;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\RemoteTestService
     */
    public function getRemoteTestService() {
        return $this->remoteTestService;
    }
    
    
    public function persist(Test $test) {
        $this->entityManager->persist($test);
        $this->entityManager->flush();
    }
    
    
//    public function startCrawl(Test $test) {
//        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_crawl_start', array(
//            'canonical-url' => urlencode($test->getWebsite()),
//            'test_id' => $test->getTestId()
//        )));
//        
//        $this->addAuthorisationToRequest($httpRequest);
//        $this->webResourceService->getHttpClientService()->get()->send($httpRequest);
//    }
    
    /**
     * 
     * @param int $limit
     * @return \webignition\WebResource\JsonDocument\JsonDocument
     */
    public function getList($limit, $excludeTypes = null) {
        $requestUrl = $this->getUrl('tests_list', array(
            'limit' => $limit
        ));
        
        $queryParts = array();               
        
        if (is_array($excludeTypes)) {
            foreach ($excludeTypes as $excludeType) {
                $queryParts[] = 'exclude-types[]=' . $excludeType;
            }
        }
        
        $queryParts[] = 'exclude-current=1';
        
        $requestUrl .= '?' . implode('&', $queryParts);        
        $request = $this->webResourceService->getHttpClientService()->getRequest($requestUrl);
        
        $this->addAuthorisationToRequest($request);
        
        /* @var $response \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($request);
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            
        }        
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
     * @return Test
     */
    public function get($canonicalUrl, $testId) {        
        if ($this->hasEntity($testId)) {           
            /* @var $test Test */
            $this->test = $this->fetchEntity($testId);          
            $this->getRemoteTestService()->setTest($this->getTest());
            
            if (($this->getTest()->getState() != 'completed' && $this->getTest()->getState() != 'cancelled')) {
                
                $this->update();             
            }          
        } else {            
            $this->test = new Test();
            $this->getTest()->setTestId($testId);
            $this->getTest()->setWebsite(new NormalisedUrl($canonicalUrl));            
            $this->getRemoteTestService()->setTest($this->getTest());
            
            if (!$this->create()) {
                return false;
            }
        }
        
        $this->entityManager->persist($this->getTest());
        $this->entityManager->flush();
        
        return $this->getTest();
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
        $remoteTest = $this->getRemoteTestService()->get();                    
        if (!$remoteTest) {
            return false;
        }

        $this->getTest()->setState($remoteTest->getState());
        $this->getTest()->setUser($remoteTest->getUser());
        $this->getTest()->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $this->getTest()->setTestId($remoteTest->getId());
        $this->getTest()->setUrlCount($remoteTest->getUrlCount());        
        $this->getTest()->setType($remoteTest->getType()); 
        
        $this->getTest()->setTaskTypes($remoteTest->getTaskTypes());
        
        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->getTest()->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->getTest()->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }          
        
        return true;
    }
    
    
    /**
     *
     * @return boolean
     */
    private function update() { 
        $remoteTest = $this->getRemoteTestService()->get();
        if (!$remoteTest instanceof RemoteTest) {
            return false;
        }
        
        $this->getTest()->setState($remoteTest->getState());
        $this->getTest()->setUrlCount($remoteTest->getUrlCount()); 
        
        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->getTest()->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->getTest()->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }   
    }
    

    
    public function getLatestRemoteSummary($canonicalUrl) {
        $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_latest', array(
            'canonical-url' => urlencode($canonicalUrl)
        )));
        
        $this->addAuthorisationToRequest($request);
        
        /* @var $testJsonDocument \webignition\WebResource\JsonDocument\JsonDocument */
        try {
            return $this->webResourceService->get($request)->getContentObject();            
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            return null;
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            if ($webResourceException->getCode() == 403) {
                return false;
            }
        }
        
        return null;         
    }

    

    
//    
//    /**
//     *
//     * @return \SimplyTestable\WebClientBundle\Entity\Test\Test 
//     */
//    private function createTestFromRemoteTestSummary() {        
//        $remoteTestSummary = $this->getRemoteTestSummary();
//
//        $this->getTest()->setState($remoteTestSummary->state);
//        $this->getTest()->setUser($remoteTestSummary->user);
//        $this->getTest()->setWebsite(new NormalisedUrl($remoteTestSummary->website));
//        $this->getTest()->setTestId($remoteTestSummary->id);
//        $this->getTest()->setUrlCount($remoteTestSummary->url_count);        
//        $this->getTest()->setType($remoteTestSummary->type);
//        
//        $taskTypes = array();
//        foreach ($remoteTestSummary->task_types as $taskTypeDetail) {
//            $taskTypes[] = $taskTypeDetail->name;
//        }  
//        
//        $this->getTest()->setTaskTypes($taskTypes);
//        $this->updateTimePeriodFromJsonObject($this->getTest()->getTimePeriod(), $remoteTestSummary);             
//        
//        return true;
//    }
    
    
    
//    /**
//     *
//     * @param \stdClass $jsonObject
//     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
//     */
//    private function createTaskFromJsonObject($jsonObject) {        
//        return $this->populateTaskFromJsonObject(new Task(), $jsonObject);
//    }
//    
//    
//    /**
//     *
//     * @param Task $task
//     * @param \stdClass $jsonObject
//     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
//     */
//    private function populateTaskFromJsonObject(Task $task, $jsonObject) {        
//        $task->setTaskId($jsonObject->id);
//        $task->setUrl($jsonObject->url);
//        $task->setState($jsonObject->state);        
//        $task->setType($jsonObject->type);
//        $this->updateTimePeriodFromJsonObject($task->getTimePeriod(), $jsonObject);       
//        
//        if (isset($jsonObject->worker)) {
//            $task->setWorker($jsonObject->worker);
//        } 
//        
//        return $task;        
//    }
    
    
//    /**
//     *
//     * @param TimePeriod $timePeriod
//     * @param type $jsonObject
//     * @return \SimplyTestable\WebClientBundle\Entity\TimePeriod 
//     */
//    private function updateTimePeriodFromJsonObject(TimePeriod $timePeriod, $jsonObject) {
//        if (isset($jsonObject->time_period)) {
//            if (isset($jsonObject->time_period->start_date_time)) {
//                $timePeriod->setStartDateTime(new \DateTime($jsonObject->time_period->start_date_time));
//            }
//
//            if (isset($jsonObject->time_period->end_date_time)) {
//                $timePeriod->setEndDateTime(new \DateTime($jsonObject->time_period->end_date_time));
//            }       
//        }
//    }
    
    
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
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return boolean
     */
    public function isFailed(Test $test) {
        $failedStatePrefix = 'failed';        
        return substr($test->getState(), 0, strlen($failedStatePrefix)) === $failedStatePrefix;
    }
//    
//    
//   
//    
//    
//    /**
//     * 
//     * @return boolean
//     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
//     * @throws \SimplyTestable\WebClientBundle\Services\CurlException
//     */
//    public function isPublic() {        
//        var_dump("cp01");
//        exit();
//        
//        return $this->getRemoteTestService()->isPublic();
//        
////        if (!isset($this->currentTestIsPublic)) {
////            if (isset($this->remoteTestSummary)) {
////                $this->currentTestIsPublic = $this->remoteTestSummary->is_public;
////            } else {
////                $request = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_is_public', array(
////                    'canonical-url' => urlencode($this->getTest()->getWebsite()),
////                    'test_id' => $this->getTest()->getTestId()
////                )));
////
////                $this->addAuthorisationToRequest($request);
////
////                try {
////                    $this->webResourceService->get($request);
////                    return true;
////                } catch (WebResourceException $webResourceException) {
////                    if ($webResourceException->getCode() == 404) {
////                        return false;
////                    }
////
////                    throw $webResourceException;
////                } catch (\Guzzle\Http\Exception\CurlException $curlException) {
////                    throw $curlException;
////                }                 
////            }            
////        }
////        
////        return $this->currentTestIsPublic;
//    }
    

    
    
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
    
}