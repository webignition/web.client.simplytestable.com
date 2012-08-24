<?php

namespace SimplyTestable\WebClientBundle\Services\Test;

use SimplyTestable\WebClientBundle\Model\Test\Test;
use SimplyTestable\WebClientBundle\Services\Task\Deserializer as TaskDeserializer;
use webignition\NormalisedUrl\NormalisedUrl;

class Deserializer {
    
    /**
     *
     * @var TaskDeserializer 
     */
    private $taskDeserializer;
    
    
    /**
     *
     * @var Test 
     */
    private $test;
    
    
    /**
     * @var \stdClass
     */
    private $testJsonObject;
    
    
    /**
     *
     * @param TaskDeserializer $taskDeserializer 
     */
    public function __construct(TaskDeserializer $taskDeserializer) {
        $this->taskDeserializer = $taskDeserializer;
    }
    
    
    /**
     *
     * @param \stdClass $testJsonObject
     * @return Test 
     */
    public function deserialize(\stdClass $testJsonObject) {
        $this->testJsonObject = $testJsonObject;        
        
        $this->test = new Test($this->testJsonObject->id);
        
        $this->test->setUser($this->testJsonObject->user);
        $this->test->setWebsite(new NormalisedUrl($this->testJsonObject->website));        
        $this->test->setState($this->testJsonObject->state);
        
        $this->deserializeTimePeriod();
        $this->deserializeTaskTypes();
        $this->deserializeTasks();
        
        $this->test->setUrlCount($this->testJsonObject->url_total);
        
        return $this->test;
    }
    

    private function deserializeTimePeriod() {
        if (isset($this->testJsonObject->time_period)) {
            if (isset($this->testJsonObject->time_period->start_date_time)) {
                $this->test->getTimePeriod()->setStartDateTime($this->testJsonObject->time_period->start_date_time);
            }

            if (isset($this->testJsonObject->time_period->end_date_time)) {
                $this->test->getTimePeriod()->setEndDateTime($this->testJsonObject->time_period->end_date_time);
            } 
        }
    }
    
    
    private function deserializeTaskTypes() {
        foreach ($this->testJsonObject->task_types as $taskType) {
            $this->test->addTaskType($taskType);
        }
    }
    
    
    private function deserializeTasks() {        
        if (isset($this->testJsonObject->tasks)) {
            foreach ($this->testJsonObject->tasks as $taskJsonObject) {
                $this->test->addTask($this->taskDeserializer->deserialize($taskJsonObject));
            }
        }
    }
}