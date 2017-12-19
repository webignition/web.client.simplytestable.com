<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered as IEFilteredController;

abstract class IEFilteredTest extends BaseTest {
    
    const ROUTER_MATCH_CONTROLLER_KEY = '_controller';
    
    
    public function testActionControllerIsIEFiltered() {
        $this->assertTrue($this->getCurrentController() instanceof IEFilteredController);
    }
    
}


