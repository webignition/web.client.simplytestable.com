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
        
        try {
            $this->getDispatcher()->dispatch(
                    $this->getListenerEventName(),
                    $this->getEvent()
            );            
        } catch (\DomainException $domainException) {
            // Invalid event data list_id
            if ($domainException->getCode() === 2) {
                $eventData = $this->get('request')->request->get('data');
                
                $logMessage = 'Controller\MailChimp\EventController ';
                
                $logMessage .= (array_key_exists('list_id', $eventData))
                    ? 'invalid list_id "' . $eventData['list_id'] . '" in event data'
                    : 'no list_id in event data';
                
                $this->getLogger()->error('Controller\MailChimp\EventController \DomainException START');
                $this->getLogger()->error($logMessage);
                $this->getLogger()->error($domainException);
                $this->getLogger()->error($domainException);                
                $this->getLogger()->error('Controller\MailChimp\EventController \DomainException END');
            } else {
                throw $domainException;
            }

        } catch (\UnexpectedValueException $unexpectedValueException) {
            // Event raw data is missing "data"
            if ($unexpectedValueException->getCode() === 2) {
                $this->getLogger()->error('Controller\MailChimp\EventController \UnexpectedValueException START');                
                $this->getLogger()->error('Controller\MailChimp\EventController event raw data is missing "data"');
                $this->getLogger()->error($this->get('request'));
                $this->getLogger()->error($unexpectedValueException);                
                $this->getLogger()->error('Controller\MailChimp\EventController \UnexpectedValueException END');                
                

            } else {
                throw $unexpectedValueException;
            }
        }
        
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
