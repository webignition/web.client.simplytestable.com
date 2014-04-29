<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\ResetPasswordChooseAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    // reset_password_choose
    
    protected function getRouteParameters() {
        return array(
            'email' => 'user@example.com',
            'token' => 'foo'
        );
    }

}