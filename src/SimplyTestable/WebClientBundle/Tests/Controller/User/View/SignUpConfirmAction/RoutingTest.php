<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\SignUpConfirmAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return array(
            'email' => 'user@example.com'
        );
    }

}