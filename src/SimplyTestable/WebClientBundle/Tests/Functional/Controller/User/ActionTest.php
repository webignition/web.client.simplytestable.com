<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::USER_CONTROLLER_NAME;
    }   
}
