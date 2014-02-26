<?php

namespace SimplyTestable\WebClientBundle\Controller\Stripe;

use Symfony\Component\HttpFoundation\Response;
use SimplyTestable\WebClientBundle\Controller\BaseController;
use SimplyTestable\WebClientBundle\Event\Stripe\Event as StripeEvent;

class EventController extends BaseController {    
    
    /**
     *
     * @var StripeEvent
     */
    private $event = null;
    
    public function indexAction() {                
        if (!$this->getEvent()->hasAcceptableName()) {
            return new Response('', 400);
        }    
        
        $this->get('event_dispatcher')->dispatch(
                'stripe.' . $this->getEvent()->getName(),
                $this->getEvent()
        );   
        
        return new Response('', 200);
    }
    
    
    private function getEvent() {
        if (is_null($this->event)) {
            $this->event = new StripeEvent($this->get('request')->request);
        }
        
        return $this->event;
    }
    
}
