<?php

namespace SimplyTestable\WebClientBundle\Model\Task;

use SimplyTestable\WebClientBundle\Model\TimePeriod;
use SimplyTestable\WebClientBundle\Model\Task\Output as TaskOutput;
use webignition\NormalisedUrl\NormalisedUrl;

class Task {
    
    /**
     *
     * @var int
     */
    private $id;
    
    
    /**
     *
     * @var NormalisedUrl 
     */
    private $url;
    
    
    /**
     *
     * @var string
     */
    private $state;
    
    
    /**
     *
     * @var string
     */
    private $worker;
    
    
    /**
     *
     * @var string
     */
    private $type;
    
    
    /**
     *
     * @var TimePeriod 
     */
    private $timePeriod;
    
    
    /**
     *
     * @var TaskOutput
     */
    private $output;
    
    /**
     *
     * @param int $id 
     */
    public function __construct($id) {
        $this->setId($id);
        $this->timePeriod = new TimePeriod();
    }
    
    
    /**
     *
     * @param int $id
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    private function setId($id) {
        $this->id = (int)$id;
        return $this;
    }
    
    
    /**
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }
    
    
    /**
     *
     * @param NormalisedUrl $url 
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setUrl(NormalisedUrl $url) {
        $this->url = $url;
        return $this;
    }
    
    
    /**
     *
     * @return NormalisedUrl 
     */
    public function getUrl() {
        return $this->url;
    }
    
    /**
     *
     * @param string $state
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setState($state) {
        $this->state = (string)$state;
        return $this;
    }
    
    /**
     *
     * @return string 
     */
    public function getState() {
        return $this->state;
    }
    
    /**
     *
     * @param string $worker
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setWorker($worker = null) {
        if (is_null($worker)) {
            $this->worker = null;
        } else {
            $this->worker = (string)$worker;
        }
        
        return $this;
    }
    
    
    /**
     *
     * @return string|null
     */
    public function getWorker() {
        return $this->worker;
    }
    
    
    /**
     *
     * @return boolean
     */
    public function hasWorker() {
        return !is_null($this->getWorker());
    }
    
    
    /**
     *
     * @param string $type
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setType($type) {
        $this->type = trim($type);
        return $this;
    }
    
    
    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    
    /**
     *
     * @param string $type
     * @return boolean
     */
    public function isType($type) {
        return strtolower($this->getType()) == trim(strtolower($type));
    }
    
    /**
     *
     * @param TimePeriod $timePeriod
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setTimePeriod(TimePeriod $timePeriod) {
        $this->timePeriod = $timePeriod;
        return $this;
    }
    
    /**
     *
     * @return TimePeriod 
     */
    public function getTimePeriod() {
        return $this->timePeriod;
    }
    
    /**
     *
     * @param TaskOutput $output
     * @return \SimplyTestable\WebClientBundle\Model\Task\Task 
     */
    public function setOutput(TaskOutput $output) {
        $this->output = $output;
        return $this;
    }
    
    
    /**
     *
     * @return TaskOutput 
     */
    public function getOutput() {
        return $this->output;
    }
    
    
    /**
     *
     * @param Task $task
     * @return boolean 
     */
    public function equals(Task $task) {
        if ((string)$this->getUrl() != (string)$task->getUrl()) {
            return false;
        }
        
        if ($this->getType() != $task->getType()) {
            return false;
        }
        
        return true;
    }
    
/**
{
  "id": 1,
  "url": "http:\/\/webignition.net\/",
  "state": "completed",
  "worker": "",
  "type": "HTML validation",
  "time_period": {
    "start_date_time": "2012-08-23T18:00:31+0100",
    "end_date_time": "2012-08-23T18:00:34+0100"
  },
  "output": {
    "output": "{\"messages\":[{\"lastLine\":59,\"lastColumn\":90,\"message\":\"An img element must have an alt attribute, except under certain conditions. For details, consult guidance on providing text alternatives for images.\",\"messageid\":\"html5\",\"type\":\"error\"},{\"lastLine\":100,\"lastColumn\":152,\"message\":\"An img element must have an alt attribute, except under certain conditions. For details, consult guidance on providing text alternatives for images.\",\"messageid\":\"html5\",\"type\":\"error\"},{\"lastLine\":109,\"lastColumn\":120,\"message\":\"An img element must have an alt attribute, except under certain conditions. For details, consult guidance on providing text alternatives for images.\",\"messageid\":\"html5\",\"type\":\"error\"}]}",
    "content_type": "application\/json"
  }
} 
 */    
    
}