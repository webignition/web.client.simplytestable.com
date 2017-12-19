<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\FunctionalTest as ControllerFunctionalTest;

abstract class FunctionalTest extends ControllerFunctionalTest {
    
    protected function getControllerName() {
        return self::TASK_CONTROLLER_NAME;
    }    

    protected function getActionName() {
        return 'resultsAction';
    }

    protected function getRoute() {
        return 'view_test_task_results_index_index';
    }    
    
}
