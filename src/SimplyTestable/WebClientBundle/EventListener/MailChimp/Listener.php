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
     * @var \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    private $mailchimpListRecipientsService;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients
     */
    private $listRecipients;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Event\MailChimp\Event
     */
    private $event;
    
    
    /**
     *
     * @param Logger $logger
     */
    public function __construct(
        Logger $logger,
        \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService $mailchimpListRecipientsService
    ) {        
        $this->logger = $logger;
        $this->mailchimpListRecipientsService = $mailchimpListRecipientsService;
    }
    
    // \DomainException
    public function onSubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {                
        $this->event = $event;
        
        if (array_key_exists('email', $event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->addRecipient($event->getData()['email']);
            $this->mailchimpListRecipientsService->persistAndFlush($listRecipients);
        }
    }   
    
    public function onUnsubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    } 
    
    public function onUpEmail(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;
        
        if (array_key_exists('old_email', $event->getData()) && array_key_exists('new_email', $event->getData())) {            
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($event->getData()['old_email']);
            $listRecipients->addRecipient($event->getData()['new_email']);
            $this->mailchimpListRecipientsService->persistAndFlush($listRecipients);
        }        
    } 
    
    public function onCleaned(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    }  
    
    
    private function handleRemoveRecipientEvent() {
        if (array_key_exists('email', $this->event->getData())) {            
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($this->event->getData()['email']);
            $this->mailchimpListRecipientsService->persistAndFlush($listRecipients);
        }        
    }
    
    
    private function getListRecipients() {
        if (is_null($this->listRecipients)) {
            $this->listRecipients = $this->mailchimpListRecipientsService->get(
                $this->mailchimpListRecipientsService->getListName($this->event->getListId())                
            );
        }
        
        return $this->listRecipients;
    }

}