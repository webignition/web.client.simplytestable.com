<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresCompletedTest as RequiresCompletedTestController;

abstract class RequiresCompletedTestTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    public function testActionControllerIsRequiresCompletedTest() {
        $this->assertTrue($this->getCurrentController() instanceof RequiresCompletedTestController);
    }
    
}


