<?php

namespace SimplyTestable\WebClientBundle\Model\Test;

use SimplyTestable\WebClientBundle\Model\TimePeriod;
use SimplyTestable\WebClientBundle\Model\Task\Task;
use webignition\NormalisedUrl\NormalisedUrl;


class Test {
    
    
    /**
     *
     * @var int
     */
    private $id;
    
    
    /**
     *
     * @var string
     */
    private $user;    
    
    
    /**
     *
     * @var NormalisedUrl 
     */
    private $website;
    
    
    /**
     *
     * @var string
     */
    private $state;
    
    
    /**
     * 
     * @var array
     */
    private $tasks;
    
    /**
     *
     * @var array
     */
    private $taskTypes;    
    
    
    /**
     *
     * @var TimePeriod
     */
    private $timePeriod;  
    
    
    /**
     *
     * @var int
     */
    private $urlCount;
    
    
    
    /**
     *
     * @param int $id 
     */
    public function __construct($id) {
        $this->setId($id);
        $this->tasks = array();
        $this->taskTypes = array();
        $this->timePeriod = new TimePeriod;
    }
    
    
    /**
     *
     * @param int $id
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test
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
     * @param string $user
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
     */
    public function setUser($user) {
        $this->user = $user;
        return $this;
    }
    
    
    /**
     *
     * @return string 
     */
    public function getUser() {
        return $this->user;
    }    
    
    
    /**
     *
     * @param NormalisedUrl $url 
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
     */
    public function setWebsite(NormalisedUrl $url) {
        $this->website = $url;
        return $this;
    }
    
    
    /**
     *
     * @return NormalisedUrl 
     */
    public function getWebsite() {
        return $this->website;
    }    
    
    
    /**
     *
     * @param string $state
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
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
     * @param Task $task
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
     */
    public function addTask(Task $task) {
        if (!$this->hasTask($task)) {
            $this->tasks[] = $task;
        }
        
        return $this;
    }
    
    /**
     *
     * @return array
     */
    public function getTasks() {
        return $this->tasks;
    }
    
    
    /**
     *
     * @param Task $task
     * @return boolean 
     */
    private function hasTask(Task $task) {
        foreach ($this->getTasks() as $comparatorTask)  {
            /* @var $comparatorTask Task */
            if ($comparatorTask->equals($task)) {
                return true;
            }
        }
        
        return false;
    }
    
    
    
    /**
     *
     * @param string $taskType
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
     */
    public function addTaskType($taskType) {
        if (!in_array($taskType, $this->taskTypes)) {
            $this->taskTypes[] = $taskType;
        }
        
        return $this;        
    }
    
    
    /**
     *
     * @return array
     */    
    public function getTaskTypes() {
        return $this->taskTypes;
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
     * @return int
     */
    public function getCompletionPercent() {       
        if ($this->getState() == 'new') {
            return 0;
        }
        
        if ($this->getState() == 'completed') {
            return 100;
        }
        
        if ($this->getTaskCount() == 0) {
            return 100;
        }        
        
        return (floor($this->getTaskCountByState('completed') / $this->getTaskCount() * 100));        
    }
    
    
    /**
     *
     * @return int 
     */
    public function getTaskCount() {
        return count($this->tasks);
    }
    
    
    /**
     *
     * @param string $state
     * @return int 
     */
    public function getTaskCountByState($state) {
        if ($this->getTaskCount() == 0) {
            return 0;
        }
        
        $total = 0;
        foreach ($this->getTasks() as $task) {
            if ($task->getState() == $state) {
                $total++;
            }
        }
        
        return $total;           
    }
    
    
    /**
     *
     * @param int $urlCount
     * @return \SimplyTestable\WebClientBundle\Model\Test\Test 
     */
    public function setUrlCount($urlCount) {
        $this->urlCount = $urlCount;
        return $this;
    }
    
    
    /**
     *
     * @return int
     */
    public function getUrlCount() {
        return $this->urlCount;
    }
    
}