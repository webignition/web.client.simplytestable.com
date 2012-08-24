<?php

namespace SimplyTestable\WebClientBundle\Services\Test;

use SimplyTestable\WebClientBundle\Model\Test\Test;
use SimplyTestable\WebClientBundle\Services\Task\Serializer as TaskSerializer;

class Serializer {
    
    /**
     *
     * @var TaskSerializer 
     */
    private $taskSerializer;
    
    
    /**
     *
     * @var Test 
     */
    private $test;
    
    
    /**
     * @var \stdClass
     */
    private $serializedTest;
    
    
    /**
     *
     * @param TaskSerializer $taskSerializer 
     */
    public function __construct(TaskSerializer $taskSerializer) {
        $this->taskSerializer = $taskSerializer;
    }
    
    
    /**
     *
     * @param Test $test
     * @return \stdClass
     */
    public function serialize(Test $test) {
        $this->test = $test;
        $this->serializedTest = new \stdClass();
        
        $this->serializedTest->id = $test->getId();
        $this->serializedTest->user = $test->getUser();
        $this->serializedTest->website = (string)$test->getWebsite();
        $this->serializedTest->state = $test->getState();
        $this->serializedTest->tasks = $this->getSerializedTasks();
        $this->serializedTest->taskTypes = $this->getSerializedTaskTypes();
        $this->serializedTest->timePeriod = $this->getSerializedTimePeriod();
        $this->serializedTest->urlCount = $test->getUrlCount();
        
        $this->serializedTest->completionPercent = $test->getCompletionPercent();
        $this->serializedTest->taskCount = $test->getTaskCount();
        $this->serializedTest->taskCountByState = $this->getSerializedTaskCountByState();

        
        return $this->serializedTest;
    }
    

    /**
     *
     * @return \stdClass 
     */
    private function getSerializedTimePeriod() {
        $serializedTimePeriod = new \stdClass();
        $serializedTimePeriod->startDateTime = $this->test->getTimePeriod()->getStartDateTime();
        $serializedTimePeriod->endDateTime = $this->test->getTimePeriod()->getEndDateTime();
        
        return $serializedTimePeriod;
    }
    
    /**
     *
     * @return array 
     */    
    private function getSerializedTaskTypes() {
        $serializedTaskTypes = array();
        
        foreach ($this->test->getTaskTypes() as $taskType) {
            $serializedTaskTypes[] = $taskType->name;
        }
        
        return $serializedTaskTypes;
    }
    
    /**
     *
     * @return array 
     */    
    private function getSerializedTasks() {
        $serializedTasks = array();
        
        foreach ($this->test->getTasks() as $task) {
            $serializedTasks[] = $this->taskSerializer->serialize($task);
        }
        
        return $serializedTasks;
    }
    
    
    /**
     *
     * @return \stdClass 
     */     
    private function getSerializedTaskCountByState() {
        $states = array(
            'queued',
            'in-progress',
            'completed'
        );
        
        $serializedTaskCountByState = new \stdClass();
        
        foreach ($states as $state) {
            $serializedTaskCountByState->{str_replace('-', '_', $state)} = $this->test->getTaskCountByState($state);
        }
        
        return $serializedTaskCountByState;
    }
}