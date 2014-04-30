<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\SignUpConfirm\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return array(
            'email' => 'user@example.com'
        );
    }

}