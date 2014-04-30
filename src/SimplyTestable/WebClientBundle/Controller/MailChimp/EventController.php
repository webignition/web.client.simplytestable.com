<?php

namespace SimplyTestable\WebClientBundle\Controller\MailChimp;

use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;

class EventController extends BaseController {    
    
    const LISTENER_EVENT_PREFIX = 'mailchimp.';
    
    /**
     *
     * @var MailChimpEvent
     */
    private $event = null;
    
    public function indexAction() {
        if (!$this->get('request')->request->has('type')) {
            return new Response('', 400);
        }
        
        if (!$this->dispatcherHasListenerForEvent()) {
            return new Response('', 400);
        }
        
        $this->getDispatcher()->dispatch(
                $this->getListenerEventName(),
                $this->getEvent()
        );   
        
        return new Response('', 200);
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Event\MailChimp\Event
     */
    private function getEvent() {
        if (is_null($this->event)) {
            $this->event = new MailChimpEvent($this->get('request')->request);
        }
        
        return $this->event;
    }
    
    
    /**
     * 
     * @return boolean
     */
    private function dispatcherHasListenerForEvent() {        
        return count($this->getDispatcher()->getListeners($this->getListenerEventName())) > 0;      
    }
    
    
    /**
     * 
     * @return \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher
     */
    private function getDispatcher() {
        return $this->get('event_dispatcher');
    }
    
    
    /**
     * 
     * @return string
     */
    private function getListenerEventName() {
        return self::LISTENER_EVENT_PREFIX . $this->getEvent()->getType();
    }
    
}
