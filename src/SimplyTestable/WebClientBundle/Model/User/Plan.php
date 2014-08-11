<?php
namespace SimplyTestable\WebClientBundle\Model\User;

use SimplyTestable\WebClientBundle\Model\Object;
use SimplyTestable\WebClientBundle\Model\AccountPlan;

class Plan extends Object {

    /**
     * @var float
     */
    private $priceModifier = 1;

    
    public function __construct($data) {
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
    
    
    /**
     * 
     * @return boolean
     */
    public function hasTrialPeriodAvailable() {
        return $this->getStartTrialPeriod() > 0;
    }


    /**
     * @param float $priceModifier
     */
    public function setPriceModifier($priceModifier) {
        $this->priceModifier = $priceModifier;
    }


    /**
     * @return float
     */
    public function getPriceModifier() {
        return $this->priceModifier;
    }


    /**
     * @return float
     */
    public function getPrice() {
        return $this->getAccountPlan()->getPrice() * $this->getPriceModifier();
    }


    /**
     * @return float
     */
    public function getOriginalPrice() {
        return $this->getAccountPlan()->getPrice();
    }
}