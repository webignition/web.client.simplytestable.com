<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\OnCleaned;

use SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\ListenerTest;

abstract class OnCleanedTest extends ListenerTest {
    
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