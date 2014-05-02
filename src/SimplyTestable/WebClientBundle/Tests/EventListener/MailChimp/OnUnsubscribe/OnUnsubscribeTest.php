<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\OnUnsubscribe;

use SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\ListenerTest;

abstract class OnUnsubscribeTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventType() {
        return 'unsubscribe';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }  
    
}