<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresPrivateUser;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresPrivateUserTest extends OnKernelRequestTest {
    
    protected function buildEvent() {
        $event = parent::buildEvent();        
        return $event;
    }    
    
}