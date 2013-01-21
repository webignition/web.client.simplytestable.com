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
            
            foreach ($remoteTasksObject as $remoteTaskObject) {                
                $task = (isset($tasks[$remoteTaskObject->id])) ? $tasks[$remoteTaskObject->id] : new Task();
                $task->setTest($test);
                $this->populateFromRemoteTaskObject($task, $remoteTaskObject);                
                $tasks[$task->getTaskId()] = $task;                
            }
        }           
        
        foreach ($tasks as $remoteTaskId => $task) {            
            if ($this->hasTaskStateChanged($task, $previousTaskStates)) {
                $tasksToPersist[] = $task;
            }
        }
        
        foreach ($tasksToPersist as $task) {            
            $this->entityManager->persist($task);            
            if ($task->hasOutput() && $this->hasTaskStateChanged($task, $previousTaskStates)) {                
                $this->entityManager->persist($task->getOutput());
            }
        }
        
        if (count($tasksToPersist)) {
           $this->entityManager->flush(); 
        }
        
        if ($test->getState() == 'completed' || $test->getState() == 'cancelled') {
            foreach ($tasks as $task) {
                $this->normaliseEndingState($task);
            }            
        }
        
        return $tasks;
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
                $this->populateOutputfromRemoteOutputObject($task, $remoteTaskObject->output);
            }            
        }        
    }
    
    
    private function populateOutputfromRemoteOutputObject(Task $task, $remoteOutputObject) {        
        $output = new Output();
        $output->setContent($remoteOutputObject->output);
        $output->setType($task->getType());
        $output->setErrorCount($remoteOutputObject->error_count);
        $output->setWarningCount($remoteOutputObject->warning_count);      
        $output->generateHash();
        
        $existingOutput = $this->getTaskOutputEntityRepository()->findOutputByhash($output->getHash());
        
        if (!is_null($existingOutput)) {
            $output = $existingOutput;
        }
        
        $task->setOutput($output);
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
        
        $httpRequest->setMethod(HTTP_METH_POST);
        
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
            $taskIds = $this->retrieveRemoteTaskIds($test);
            
            foreach ($taskIds as $taskIdValue) {
                $taskId = new TaskId();
                $taskId->setTaskId($taskIdValue);
                $taskId->setTest($test);
                $test->addTaskId($taskId);
                
                $this->entityManager->persist($taskId);
            }
            
            $this->entityManager->flush();              
        }
        
        $taskIds = array();
        foreach ($test->getTaskIds() as $taskId) {
            $taskIds[] = $taskId->getTaskId();
        }
        
        return $taskIds;
    }
    
    
    /**
     *
     * @param Test $test
     * @return array 
     */
    private function retrieveRemoteTaskIds(Test $test) {
        $httpRequest = $this->getAuthorisedHttpRequest(
            $this->getUrl('test_task_ids', array(
                'canonical-url' => (string)$test->getWebsite(),
                'test_id' => $test->getTestId()
        )));

        try {
            return $this->webResourceService->get($httpRequest)->getContentObject();          
        } catch (\webignition\Http\Client\CurlException $curlException) {
            
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 403) {
                return false;
            }
        }          
    }
    
    
//    public function getRemoteTaskIds(Test $test) {
//        
//    }
    
//    private function update($taskIds = array()) {
//        
//    }
    
    
    /**
     *
     * @param Test $test
     * @param int $task_id
     * @return Task 
     */
    public function get(Test $test, $task_id) {
        $task = $this->getEntityRepository()->findOneBy(array(
            'taskId' => $task_id,
            'test' => $test
        ));
        
        if ($task == null) {
            return $task;
        }
        
        if ($test->getState() == 'completed' || $test->getState() == 'cancelled') {
            $this->normaliseEndingState($task);           
        }        
        
        return $this->taskOutputService->setParsedOutput($task);        
    }
//    
//    
//    /**
//     *
//     * @param Test $test
//     * @param array $task_ids
//     * @return Task 
//     */
//    public function getCollection(Test $test, $task_ids) {
//        $tasks = $this->getEntityRepository()->findBy(array(
//            'id' => $task_ids,
//            'test' => $test
//        ));
//        
//        foreach ($tasks as $task) {
//            $this->taskOutputService->setParsedOutput($task); 
//        }
//        
//        return $tasks;
//    }
//    
//    
//    /**
//     *
//     * @param Task $task
//     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
//     */
//    public function markCancelled(Task $task) {
//        $task->setState('cancelled');
//        $this->entityManager->persist($task);
//        $this->entityManager->flush(); 
//        
//        return $task;
//    }
//    
//    
//    /**
//     *
//     * @param Task $task
//     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
//     */
//    public function markFailed(Task $task) {
//        $task->setState('failed');
//        $this->entityManager->persist($task);
//        $this->entityManager->flush(); 
//        
//        return $task;
//    }    
//    
//    
//    /**
//     * 
//     * @param array $taskIds
//     * @return array 
//     */
//    public function getRemoteTaskIds($taskIds = null) {
//        $queryBuilder = $this->getEntityRepository()->createQueryBuilder('Task');
//        $queryBuilder->select('Task.taskId');
//        
//        if (is_array($taskIds)) {
//            $queryBuilder->where('Task.id IN ('.  implode(',', $taskIds).')');
//        }        
//
//        $result = $queryBuilder->getQuery()->getResult();        
//
//        $remoteTaskIds = array();
//        foreach ($result as $resultItem) {
//            $remoteTaskIds[] = (int)$resultItem['taskId'];
//        }
//
//        return $remoteTaskIds;
//    }
    
    
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