<?php
namespace SimplyTestable\WebClientBundle\Services;

class TaskTypeService {

    private $taskTypes;


    /**
     * @param array $taskTypes
     */
    public function setTaskTypes($taskTypes) {
        $this->taskTypes = $taskTypes;
    }



}