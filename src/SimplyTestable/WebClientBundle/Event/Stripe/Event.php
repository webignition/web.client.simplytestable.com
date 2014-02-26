<?php

namespace SimplyTestable\WebClientBundle\Event\Stripe;

use Symfony\Component\EventDispatcher\Event as BaseEvent;
use SimplyTestable\WebClientBundle\Exception\Stripe\Event\Exception as StripeEventException;

class Event extends BaseEvent {
    
    const NAME_KEY = 'event';
    const USER_KEY = 'user';
    
    /**
     *
     * @var string[]
     */
    private $acceptableNames = array(
        'customer.subscription.created',
        'customer.subscription.trial_will_end',
        'customer.subscription.updated',
        'invoice.created',
        'invoice.payment_succeeded',
        'invoice.payment_failed'
    );    
    
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $data;
    
    

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\ParameterBag $data
     */
    public function __construct(\Symfony\Component\HttpFoundation\ParameterBag $data) {
        $this->data = $data;
    }
    
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    public function getData() {
        return $this->data;
    }  
    
    
    /**
     * 
     * @return string|null
     */
    public function getName() {
        return $this->data->get(self::NAME_KEY);
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasAcceptableName() {
        return in_array($this->getName(), $this->acceptableNames);
    }
    
    
    /**
     * 
     * @return string
     */
    public function getUser() {
        return $this->data->get(self::USER_KEY);
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasUser() {
        return !is_null($this->getUser());
    }
    
}