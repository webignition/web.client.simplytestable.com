<?php
namespace SimplyTestable\WebClientBundle\Model;

use SimplyTestable\WebClientBundle\Model\Object;

class AccountPlan extends Object {    
    
    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->getDataProperty('name');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function getIsPremium() {
        return $this->getDataProperty('is_premium');
    }
    
}