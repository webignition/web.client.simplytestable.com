<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnUpEmail;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\ListenerTest;

abstract class OnUpEmailTest extends ListenerTest {
    
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