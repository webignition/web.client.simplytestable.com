<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::TASK_CONTROLLER_NAME;
    }   
}
