<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\UserAccountPlan;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::USER_ACCOUNT_PLAN_CONTROLLER_NAME;
    }   
}
