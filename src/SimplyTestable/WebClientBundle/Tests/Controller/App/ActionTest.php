<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::APP_CONTROLLER_NAME;
    }   
}
