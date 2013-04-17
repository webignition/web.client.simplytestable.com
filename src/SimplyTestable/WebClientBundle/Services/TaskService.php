<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Entity\Test\TaskId;

class TaskService extends CoreApplicationService {    
    
    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\Task\Task';      
    
    private $finishedStates = array(
        'completed',
        'cancelled',
        'awaiting-cancellation',
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached'
    );
    
    private $cancelledStates = array(
        'cancelled',
        'awaiting-cancellation'        
    );
    
    
    private $failedStates = array(
        'failed-no-retry-available',
        'failed-retry-available',
        'failed-retry-limit-reached'        
    );
    
    private $incompleteStates = array(
        'queued',
        'queued-for-assignment',
        'in-progress'
    );
    
    
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
     * @var \SimplyTestable\WebClientBundle\Repository\TaskOutputRepository
     */
    private $taskOutputRepository;    
    
    
    public function __construct(
        EntityManager $entityManager,
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutputService $taskOutputService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->entityManager = $entityManager; 
        $this->taskOutputService = $taskOutputService;
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Repository\TaskRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }
        
        return $this->entityRepository;
    }
    
    
    /**
     *
     * @param Test $test
     * @param array $remoteTaskIds
     * @return array 
     */
    public function getCollection(Test $test, $remoteTaskIds = null) {
        if (!is_array($remoteTaskIds)) {
            $remoteTaskIds = $this->getRemoteTaskIds($test);
        }
        
        $existenceResult = $this->getEntityRepository()->getCollectionExistsByTestAndRemoteId($test, $remoteTaskIds);
        $tasksToRetrieve = array();
        $localTasksToUpdate = array();
        
        foreach ($existenceResult as $remoteTaskId => $exists) {
            if ($exists) {
                $localTasksToUpdate[] = $remoteTaskId;
            } else {
                $tasksToRetrieve[] = $remoteTaskId;
            }
        }         
        
        $tasksToPersist = array();
        $tasks = array();
        $previousTaskStates = array();     
        
        if (count($localTasksToUpdate)) {            
            $tasks = $this->getEntityRepository()->getCollectionByTestAndRemoteId($test, $remoteTaskIds);
            $previousTaskStates = $this->getTaskStates($tasks);
            
            foreach ($tasks as $task) {
                if (!$this->isFinished($task)) {
                    $tasksToRetrieve[] = $task->getTaskId();
                }
            }                        
        }
        
        if (count($tasksToRetrieve)) {
            $remoteTasksObject = $this->retrieveRemoteCollection($test, $tasksToRetrieve);     
            
            $outputs = array();
            
            foreach ($remoteTasksObject as $remoteTaskObject) {
                if (isset($remoteTaskObject->output)) {
                    $outputs[] = $this->populateOutputfromRemoteOutputObject($remoteTaskObject);        
                }
            }
            
            if (count($outputs)) {
                $outputs = $this->minimiseOutputCollection($outputs);
                
                if (count($outputs)) {
                    foreach ($outputs as $output) {
                        $this->entityManager->persist($output);
                    }
                    
                    $this->entityManager->flush();                     
                };                
            }
            
            foreach ($remoteTasksObject as $remoteTaskObject) {                
                $task = (isset($tasks[$remoteTaskObject->id])) ? $tasks[$remoteTaskObject->id] : new Task();
                $task->setTest($test);
                $this->populateFromRemoteTaskObject($task, $remoteTaskObject);                
                $tasks[$task->getTaskId()] = $task;                
            }
        }
        
        foreach ($tasks as $remoteTaskId => $task) {            
            if ($this->hasTaskStateChanged($task, $previousTaskStates)) {
                $tasksToPersist[$task->getTaskId()] = $task;
            }
        }  
 
        
        if (count($tasksToPersist)) {
            foreach ($tasksToPersist as $task) {
                /* @var $task Task */
                $this->entityManager->persist($task);
            }            
            
            $this->entityManager->flush();
        }         
        
        if ($test->getState() == 'completed' || $test->getState() == 'cancelled') {
            foreach ($tasks as $task) {
                $this->normaliseEndingState($task);
            }            
        }
        
        return $tasks;
    }
    
    
    private function minimiseOutputCollection($outputs) {
        $processedHashes = array();
        
        foreach ($outputs as $outputIndex => $output) {
            /* @var $output Output */
            if (in_array($output->getHash(), $processedHashes) || $output->hasId()) {
                unset($outputs[$outputIndex]);
            } else {
                $processedHashes[] = $output->getHash();
            }
        }
        
        return $outputs;
    }
    
    
    private function normaliseEndingState(Task $task) {
        if ($this->isIncomplete($task)) {
            return $task->setState('cancelled');
        }        
        
        if ($this->isCancelled($task)) {
            return $task->setState('cancelled');
        }

        if ($this->isFailed($task)) {
            return $task->setState('failed');
        }
        
        if ($this->isIncomplete($task)) {
            return $task->setState('cancelled');
        }     
    }
    
    
    /**
     *
     * @param Task $task
     * @param array $previousTaskStates
     * @return boolean 
     */
    private function hasTaskStateChanged(Task $task, $previousTaskStates) {
        if (!isset($previousTaskStates[$task->getTaskId()])) {
            return true;
        }
        
        return $previousTaskStates[$task->getTaskId()] != $task->getState();   
    }
    
    
    /**
     *
     * @param array $tasks
     * @return array 
     */
    private function getTaskStates($tasks) {
        $taskStates = array();
        
        foreach ($tasks as $task) {
            $taskStates[$task->getTaskId()] = $task->getState();
        }
        
        return $taskStates;
    }
    
    
    /**
     *
     * @param Task $task
     * @param \stdClass $remoteTaskObject 
     */
    private function populateFromRemoteTaskObject(Task $task, \stdClass $remoteTaskObject) {
        $propertyToMethodMap = array(
            'id' => 'setTaskId',
            'url' => 'setUrl',
            'state' => 'setState',
            'worker'=> 'setWorker',
            'type' => 'setType'
        );
        
        foreach ($propertyToMethodMap as $propertyName => $methodName) {
            $task->$methodName($remoteTaskObject->$propertyName);
        }
        
        if (isset($remoteTaskObject->time_period)) {         
            $this->updateTimePeriodFromJsonObject($task->getTimePeriod(), $remoteTaskObject->time_period);
        }        
        
        if (isset($remoteTaskObject->output)) {
            if (!$task->hasOutput()) {
                $task->setOutput($this->populateOutputfromRemoteOutputObject($remoteTaskObject));
            }            
        }        
    }
    
    
    private function populateOutputfromRemoteOutputObject($remoteTaskObject) {        
        $remoteOutputObject = $remoteTaskObject->output;
        
        $output = new Output();
        $output->setContent($remoteOutputObject->output);
        $output->setType($remoteTaskObject->type);
        $output->setErrorCount($remoteOutputObject->error_count);
        $output->setWarningCount($remoteOutputObject->warning_count);      
        $output->generateHash();
        
        $existingOutput = $this->getTaskOutputEntityRepository()->findOutputByhash($output->getHash());
        
        if (!is_null($existingOutput)) {            
            $output = $existingOutput;
        }
        
        return $output;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Repository\TaskOutputRepository
     */
    private function getTaskOutputEntityRepository() {
        if (is_null($this->taskOutputRepository)) {
            $this->taskOutputRepository = $this->entityManager->getRepository('SimplyTestable\WebClientBundle\Entity\Task\Output');
        }
        
        return $this->taskOutputRepository;
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
     * @param array $remoteTaskIds
     * @return array 
     */
    private function retrieveRemoteCollection(Test $test, $remoteTaskIds) {        
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_tasks', array(
                'canonical-url' => (string)$test->getWebsite(),
                'test_id' => $test->getTestId()
        )));
        
        $httpRequest->setMethod(\Guzzle\Http\Message\Request::POST);
        
        $httpRequest->setPostFields(array(
            'taskIds' => implode(',', $remoteTaskIds)            
        ));

        try {
            return $this->webResourceService->get($httpRequest)->getContentObject(); 
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                return false;
            }
        }       
    }  
    
    
    /**
     *
     * @param Test $test
     * @return array 
     */
    public function getRemoteTaskIds(Test $test) {
        if (($test->getState() == 'new' || $test->getState() == 'preparing')) {
            return array();
        }
        
        if (!$test->hasTaskIds()) {            
            $test->setTaskIdColletion(implode(',', $this->retrieveRemoteTaskIds($test)));

            $this->entityManager->persist($test);
            $this->entityManager->flush();
        }
        
        return $test->getTaskIds();
    }
    
    
    /**
     *
     * @param Test $test
     * @param int $limit
     * @return array 
     */
    public function getUnretrievedRemoteTaskIds(Test $test, $limit) {
        $remoteTaskIds = $this->getRemoteTaskIds($test);        
        $retrievedRemoteTaskIds = $this->getEntityRepository()->findRetrievedRemoteTaskIds($test);
        
        $unretrievedRemoteTaskIds = array();
        
        foreach ($remoteTaskIds as $remoteTaskId) {
            if (!in_array($remoteTaskId, $retrievedRemoteTaskIds)) {
                $unretrievedRemoteTaskIds[] = $remoteTaskId;
            }
            
            if (count($unretrievedRemoteTaskIds) === $limit) {
                return $unretrievedRemoteTaskIds;
            }
        }
        
        return $unretrievedRemoteTaskIds;
    }
    
    
    /**
     *
     * @param Test $test
     * @return array 
     */
    private function retrieveRemoteTaskIds(Test $test) {
        $httpRequest = $this->webResourceService->getHttpClientService()->getRequest($this->getUrl('test_task_ids', array(
                'canonical-url' => (string)$test->getWebsite(),
                'test_id' => $test->getTestId()
        )));
        
        $this->addAuthorisationToRequest($httpRequest);
        
        return $this->webResourceService->get($httpRequest)->getContentObject();
    }
    
    
    /**
     *
     * @param Test $test
     * @param int $task_id
     * @return Task 
     */
    public function get(Test $test, $task_id) {
        if (!$this->hasEntity($task_id)) {
            $this->getCollection($test, array($task_id)); 
        }        
        
        $task = $this->getEntityRepository()->findOneBy(array(
            'taskId' => $task_id,
            'test' => $test
        ));
        
        if (is_null($task)) {
            return null;
        }
        
        if ($test->getState() == 'completed' || $test->getState() == 'cancelled') {
            $this->normaliseEndingState($task);           
        }        
        
        return $this->taskOutputService->setParsedOutput($task);       
    }
    
    
    /**
     *
     * @param int $testId
     * @return boolean
     */
    private function hasEntity($testId) {        
        return $this->getEntityRepository()->hasByTaskId($testId);
    }    
    
    
    /**
     *
     * @param Task $task
     * @return boolean
     */
    private function isFinished(Task $task) {
        return in_array($task->getState(), $this->finishedStates);
    }
    
    
    /**
     *
     * @param Task $task
     * @return boolean
     */
    private function isCancelled(Task $task) {
        return in_array($task->getState(), $this->cancelledStates);
    }    
    
    
    /**
     *
     * @param Task $task
     * @return boolean
     */
    private function isFailed(Task $task) {
        return in_array($task->getState(), $this->failedStates);
    }     
    
    /**
     *
     * @param Task $task
     * @return boolean
     */
    private function isIncomplete(Task $task) {
        return in_array($task->getState(), $this->incompleteStates);
    }        
}