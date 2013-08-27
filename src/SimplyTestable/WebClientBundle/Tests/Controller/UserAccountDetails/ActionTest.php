<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getControllerName() {
        return self::USER_ACCOUNT_DETAILS_CONTROLLER_NAME;
    }   
}
