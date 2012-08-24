<?php

namespace SimplyTestable\WebClientBundle\Services\TaskDriver;
//
//use SimplyTestable\WorkerBundle\Entity\Task\Task;
//use SimplyTestable\WorkerBundle\Entity\Task\Type\Type as TaskType;
//use SimplyTestable\WorkerBundle\Services\TaskTypeService;
//use SimplyTestable\WorkerBundle\Services\StateService;
//use SimplyTestable\WorkerBundle\Services\WebResourceService;

abstract class TaskStatusParser {
    
    
    
    /**
     * @param Task $task
     * @return \SimplyTestable\WorkerBundle\Entity\Task\Output 
     */
    public function parse(Task $task) {        
        $rawOutput = $this->execute($task);
        $output = new \SimplyTestable\WorkerBundle\Entity\Task\Output();
        $output->setOutput($rawOutput);
        $output->setContentType($this->getOutputContentType());
        $output->setState($this->stateService->fetch(self::OUTPUT_STARTING_STATE));
        
        return $output;
    }
    
    
    /**
     * @return string 
     */
    abstract protected function execute(Task $task);
    
    
    /**
     * @return \webignition\InternetMediaType\InternetMediaType
     */
    abstract protected function getOutputContentType();


    
    /**
     *
     * @return string
     */
    public function getOutput() {
        return $this->output;
    }
    
    
    /**
     *
     * @param TaskType $taskType 
     */
    public function addTaskType(TaskType $taskType) {
        if (!$this->handles($taskType)) {
            $this->taskTypes[] = $taskType;
        }
    }
    
    
    /**
     *
     * @param TaskType $taskType
     * @return boolean 
     */
    public function handles(TaskType $taskType) {
        foreach ($this->taskTypes as $currentTaskType) {
            /* @var $currentTaskType TaskType */
            if ($currentTaskType->equals($taskType)) {
                return true;
            }
        }
        
        return false;
    }
    
    
}