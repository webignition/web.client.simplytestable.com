<?php
namespace SimplyTestable\WebClientBundle\Model\User\Team;

use SimplyTestable\WebClientBundle\Model\Object;

class Summary extends Object {
    
    /**
     * 
     * @param \stdClass $data
     */
    public function __construct(\stdClass $data) {
        parent::__construct($data);
    }


    /**
     * @return bool
     */
    public function isInTeam() {
        return $this->getDataProperty('in');
    }

    /**
     * @return bool
     */
    public function hasInvite() {
        return $this->getDataProperty('has_invite');
    }
    
}