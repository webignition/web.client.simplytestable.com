<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\OnSubscribe;

use SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\ListenerTest;

abstract class OnSubscribeTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventType() {
        return 'subscribe';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }  
    
}