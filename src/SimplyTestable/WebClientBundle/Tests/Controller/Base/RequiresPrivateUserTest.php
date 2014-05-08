<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresPrivateUser as RequiresPrivateUserController;

abstract class RequiresPrivateUserTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    
    public function testActionControllerIsRequiresPrivateUser() {
        $this->assertTrue($this->getCurrentController() instanceof RequiresPrivateUserController);
    }
    
}


