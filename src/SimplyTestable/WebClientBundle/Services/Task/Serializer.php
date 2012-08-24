<?php

namespace SimplyTestable\WebClientBundle\Services\Task;

use SimplyTestable\WebClientBundle\Model\Task\Task;
use SimplyTestable\WebClientBundle\Model\Task\Output;

class Serializer {
    
    /**
     *
     * @var Task
     */
    private $task;
    
    
    /**
     *
     * @var \stdClass
     */
    private $serialisedTask;    
    
    
    
    /**
     *
     * @param Task $task
     * @return \stdClass
     */
    public function serialize(Task $task) {
        $this->task = $task;
        $this->serialisedTask = new \stdClass();
  
        $this->serialisedTask->id = $task->getId();
        $this->serialisedTask->url = (string)$task->getUrl();
        $this->serialisedTask->state = $task->getState();
        $this->serialisedTask->worker = $task->getWorker();
        $this->serialisedTask->type = $task->getType();
        $this->serialisedTask->timePeriod = $this->getSerializedTimePeriod();
        // $this->serialisedTask->output = $this->getSerialisedOutput();
        
        return $this->serialisedTask;
    }
    
    /**
     *
     * @return \stdClass 
     */
    private function getSerializedTimePeriod() {
        $serializedTimePeriod = new \stdClass();
        $serializedTimePeriod->startDateTime = $this->task->getTimePeriod()->getStartDateTime();
        $serializedTimePeriod->endDateTime = $this->task->getTimePeriod()->getEndDateTime();
        
        return $serializedTimePeriod;
    }
    
}