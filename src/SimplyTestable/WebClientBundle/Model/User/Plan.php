<?php
namespace SimplyTestable\WebClientBundle\Model\User;

use SimplyTestable\WebClientBundle\Model\Object;
use SimplyTestable\WebClientBundle\Model\AccountPlan;

class Plan extends Object {
    
    public function __construct(\stdClass $data) {
        parent::__construct($data);        
        $this->setDataProperty('plan', new AccountPlan($this->getDataProperty('plan')));
    }
    
    
    /**
     * 
     * @return int
     */
    public function getStartTrialPeriod() {
        return $this->getDataProperty('start_trial_period');
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Model\AccountPlan
     */
    public function getAccountPlan() {
        return $this->getDataProperty('plan');
    }
}