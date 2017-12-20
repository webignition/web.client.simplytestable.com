<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\SignUp\Confirm\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return array(
            'email' => 'user@example.com'
        );
    }

}