<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    protected function getControllerName() {
        return self::TASK_CONTROLLER_NAME;
    }    

    protected function getActionName() {
        return 'resultsAction';
    }

    protected function getRoute() {
        return 'app_task_results';
    }    
    
}
