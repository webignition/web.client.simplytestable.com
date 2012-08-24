<?php

namespace SimplyTestable\WebClientBundle\Services\Task;

use SimplyTestable\WebClientBundle\Entity\Task\Task;
use webignition\NormalisedUrl\NormalisedUrl;


class Deserializer {
    
    /**
     *
     * @var Task
     */
    private $task;
    
    
    /**
     *
     * @var \stdClass
     */
    private $taskJsonObject;    
    
    
    
    /**
     *
     * @param \stdClass $taskJsonObject
     * @return Task 
     */
    public function deserialize(\stdClass $taskJsonObject) {
        $this->taskJsonObject = $taskJsonObject;
        $this->task = new Task($this->taskJsonObject->id);
        
        $this->task->setUrl(new NormalisedUrl($this->taskJsonObject->url));
        $this->task->setState($this->taskJsonObject->state);
        
        if (isset($this->taskJsonObject->worker) && $this->taskJsonObject->worker != '') {
            $this->task->setWorker($this->taskJsonObject->worker);
        }
        
        $this->task->setType($this->taskJsonObject->type);
        
        $this->deserializeTimePeriod();
        
        return $this->task;
    }
    
    private function deserializeTimePeriod() {
        if (isset($this->taskJsonObject->time_period)) {
            if (isset($this->taskJsonObject->time_period->start_date_time)) {
                $this->task->getTimePeriod()->setStartDateTime($this->taskJsonObject->time_period->start_date_time);
            }

            if (isset($this->taskJsonObject->time_period->end_date_time)) {
                $this->task->getTimePeriod()->setEndDateTime($this->taskJsonObject->time_period->end_date_time);
            } 
        }
    }    
    
}