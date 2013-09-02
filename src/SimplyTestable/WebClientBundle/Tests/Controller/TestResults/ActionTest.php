<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::TEST_RESULTS_CONTROLLER_NAME;
    }   
}
