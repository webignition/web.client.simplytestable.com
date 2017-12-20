<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnSubscribe;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\ListenerTest;

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