<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::USER_ACCOUNT_PLAN_CONTROLLER_NAME;
    }   
}
