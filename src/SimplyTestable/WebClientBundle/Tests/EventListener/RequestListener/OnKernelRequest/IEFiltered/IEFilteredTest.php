<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\IEFiltered;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class IEFilteredTest extends OnKernelRequestTest {
    
    protected function buildEvent() {
        $event = parent::buildEvent();
        $event->getRequest()->server->set('HTTP_USER_AGENT', $this->getHttpUserAgent());
        
        return $event;
    }
    
    abstract protected function getHttpUserAgent();
    
    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\User\View\SignUp\IndexController::indexAction';
    }
    
    protected function getControllerRouteString() {
        return 'user_view_signup_index';
    }
    
    
}