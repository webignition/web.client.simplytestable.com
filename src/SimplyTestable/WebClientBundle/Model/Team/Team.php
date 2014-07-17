<?php
namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\Object;

class Team extends Object {
    
    /**
     * 
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data) {
        parent::__construct($data);
    }


    /**
     * @return string
     */
    public function getLeader() {
        return $this->getDataProperty('team')->leader;
    }


    /**
     * @return string
     */
    public function getName() {
        return $this->getDataProperty('team')->name;
    }
    
}