<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser as RequiresValidUserController;

abstract class RequiresValidUserTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    public function testActionControllerIsRequiresPrivateUser() {
        $this->assertTrue($this->getCurrentController() instanceof RequiresValidUserController);
    }
    
}


