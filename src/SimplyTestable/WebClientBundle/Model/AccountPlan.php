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


    /**
     * @return bool
     */
    public function getIsCustom() {
        return preg_match('/-custom$/', $this->getName()) > 0;
    }


    /**
     * @return int
     */
    public function getPrice() {
        return (int)$this->getDataProperty('price');
    }


    /**
     * @return int
     */
    public function getUrlsPerJob() {
        return (int)$this->getDataProperty('urls_per_job');
    }


    /**
     * @return int
     */
    public function getCreditsPerMonth() {
        return (int)$this->getDataProperty('credits_per_month');
    }
    
}