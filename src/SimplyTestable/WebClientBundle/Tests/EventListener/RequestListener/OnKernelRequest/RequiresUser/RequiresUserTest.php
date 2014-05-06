<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresUser;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresUserTest extends OnKernelRequestTest {
    
    protected function buildEvent() {
        $event = parent::buildEvent();        
        return $event;
    }    
    
}