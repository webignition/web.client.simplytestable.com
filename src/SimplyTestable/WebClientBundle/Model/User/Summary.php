<?php
namespace SimplyTestable\WebClientBundle\Model\User;

use SimplyTestable\WebClientBundle\Model\Object;

class Summary extends Object {    
    
    /**
     * 
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data) {
        parent::__construct($data);
        
        if ($this->hasDataProperty('stripe_customer')) {
            $this->setDataProperty('stripe_customer', new \webignition\Model\Stripe\Customer(json_encode($this->getDataProperty('stripe_customer'))));
        }
        
        if ($this->hasDataProperty('user_plan')) {
            $this->setDataProperty('user_plan', new Plan($this->getDataProperty('user_plan')));
        }
    }
    
    
    /**
     * 
     * @return \webignition\Model\Stripe\Customer|null
     */
    public function getStripeCustomer() {
        return $this->getDataProperty('stripe_customer');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\User\Plan
     */
    public function getPlan() {
        return $this->getDataProperty('user_plan');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasPlan() {
        return !is_null($this->getPlan());
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function hasStripeCustomer() {
        return !is_null($this->getStripeCustomer());
    }
    
    
    /**
     * 
     * @return int
     */
    public function getDayOfTrialPeriod() {
        if (!$this->hasStripeCustomer()) {
            return null;
        }
        
        if (!$this->getStripeCustomer()->hasSubscription()) {
            return null;
        }
        
        if (!$this->getStripeCustomer()->getSubscription()->isTrialing()) {
            return null;
        }
        
        $trialPeriodRemaining = $this->getStripeCustomer()->getSubscription()->getTrialPeriod()->getEnd() - time();
        return (int)($this->getPlan()->getStartTrialPeriod() - floor($trialPeriodRemaining / 86400));
    }
    
    
    /**
     * 
     * @return \stdClass
     */
    public function getPlanConstraints() {
        return $this->getDataProperty('plan_constraints');
    }
    
}