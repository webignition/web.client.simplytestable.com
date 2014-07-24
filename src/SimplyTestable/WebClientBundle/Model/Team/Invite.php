<?php
namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\Object;

class Invite extends Object {


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


    /**
     * @return string
     */
    public function getToken() {
        return $this->getDataProperty('token');
    }
    
}