<?php
namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\Object;

class Invite extends Object {
    
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
    public function getUser() {
        return $this->getDataProperty('user');
    }


    /**
     * @return string
     */
    public function getTeam() {
        return $this->getDataProperty('team');
    }
    
}