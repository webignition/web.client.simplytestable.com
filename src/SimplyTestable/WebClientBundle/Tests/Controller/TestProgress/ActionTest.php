<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestProgress;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::TEST_PROGRESS_CONTROLLER_NAME;
    }   
}
