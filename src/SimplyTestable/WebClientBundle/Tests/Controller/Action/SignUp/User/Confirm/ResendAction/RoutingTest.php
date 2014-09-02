<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return [
            'email' => 'user@example.com'
        ];
    }

}