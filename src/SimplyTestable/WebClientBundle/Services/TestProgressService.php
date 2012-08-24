<?php
namespace SimplyTestable\WebClientBundle\Services;


class TestProgressService {
    
    private $testData;
    
    public function setTestData($testData) {
        $this->testData = $testData;
    }
    
    
    /**
     *
     * @return int 
     */
    public function getCompletionPercent() {
        if (is_null($this->testData)) {
            return 0;
        }
        
        if ($this->testData->state == 'new') {
            return 0;
        }
        
        if ($this->testData->state == 'completed') {
            return 100;
        }
        
        if ($this->getTaskTotal() == 0) {
            return 100;
        }
        
        return (floor($this->getTaskTotalByState('completed') / $this->getTaskTotal() * 100));
    }
    
    
    
    
    
    /**
     *
     * @return int 
     */
    public function getTaskTotal() {
        if (is_null($this->testData)) {
            return 0;
        }
        
        if (!isset($this->testData->tasks)) {
            return 0;
        }
        
        return count($this->testData->tasks);
    }
    
    
    /**
     *
     * @param string $state
     * @return int 
     */
    public function getTaskTotalByState($state) {
        if ($this->getTaskTotal() == 0) {
            return 0;
        }
        
        $total = 0;
        foreach ($this->testData->tasks as $task) {
            if ($task->state == $state) {
                $total++;
            }
        }
        
        return $total;       
    }
    
}