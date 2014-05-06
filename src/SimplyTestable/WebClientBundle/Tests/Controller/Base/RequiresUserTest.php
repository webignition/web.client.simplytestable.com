<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresUser as RequiresUserController;

abstract class RequiresUserTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    
    public function testActionControllerIsRequiresUser() {
        $this->assertTrue($this->getCurrentController() instanceof RequiresUserController);
    }
    
}


