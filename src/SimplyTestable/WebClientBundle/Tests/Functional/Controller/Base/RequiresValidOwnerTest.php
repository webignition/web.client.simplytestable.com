<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner as RequiresValidOwnerController;

abstract class RequiresValidOwnerTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    public function testActionControllerIsRequiresValidOwner() {
        $this->assertTrue($this->getCurrentController() instanceof RequiresValidOwnerController);
    }
    
}


