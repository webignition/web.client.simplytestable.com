<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\SignUp\Team\Invite\AcceptAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return [
            'token' => 'foo'
        ];
    }

}