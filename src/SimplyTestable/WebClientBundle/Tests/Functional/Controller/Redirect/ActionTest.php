<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::REDIRECT_CONTROLLER_NAME;
    }   
}
