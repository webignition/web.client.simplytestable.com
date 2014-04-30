<?php

namespace SimplyTestable\WebClientBundle\EventListener\MailChimp;

use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;

class Listener {    
    
    /**
     *
     * @var Logger
     */
    private $logger;
    
    
    /**
     *
     * @param Logger $logger
     */
    public function __construct( Logger $logger) {        
        $this->logger = $logger;
    }
    
    
    public function onSubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        
    }   
    
    public function onUnsubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        
    } 
    
    public function onUpEmail(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        
    } 
    
    public function onCleaned(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        
    }     

}