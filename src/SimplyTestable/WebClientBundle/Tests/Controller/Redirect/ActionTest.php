<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::REDIRECT_CONTROLLER_NAME;
    }   
}
