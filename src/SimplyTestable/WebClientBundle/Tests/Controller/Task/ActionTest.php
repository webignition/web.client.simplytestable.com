<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::TASK_CONTROLLER_NAME;
    }   
}
