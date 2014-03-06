<?php

namespace SimplyTestable\WebClientBundle\Controller\Stripe;

use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventController extends BaseController {    
    
    const LISTENER_EVENT_PREFIX = 'stripe.';
    
    /**
     *
     * @var StripeEvent
     */
    private $event = null;
    
    public function indexAction() {
        if (!$this->getEvent()->hasUser()) {
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
     * @return \SimplyTestable\WebClientBundle\Event\Stripe\Event
     */
    private function getEvent() {
        if (is_null($this->event)) {
            $this->event = new StripeEvent($this->get('request')->request);
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
        return self::LISTENER_EVENT_PREFIX . $this->getEvent()->getName();
    }
    
}
