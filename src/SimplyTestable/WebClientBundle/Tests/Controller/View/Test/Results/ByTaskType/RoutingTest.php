<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\ByTaskType\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RoutingTest as BaseRoutingTest;

class RoutingTest extends BaseRoutingTest {

    protected function getRouteParameters() {
        return array(
            'website' => 'http://example.com',
            'test_id' => 1,
            'task_type' => 'html+validation'
        );
    }

}