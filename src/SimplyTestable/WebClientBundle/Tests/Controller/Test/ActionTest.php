<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Test;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::TEST_CONTROLLER_NAME;
    }   
}
