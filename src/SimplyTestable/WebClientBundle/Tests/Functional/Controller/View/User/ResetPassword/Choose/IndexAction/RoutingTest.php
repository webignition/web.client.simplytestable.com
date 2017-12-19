<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\ResetPassword\Choose\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    // reset_password_choose
    
    protected function getRouteParameters() {
        return array(
            'email' => 'user@example.com',
            'token' => 'foo'
        );
    }

}