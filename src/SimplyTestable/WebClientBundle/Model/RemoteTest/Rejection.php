<?php
namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

class Rejection extends AbstractStandardObject {
    
    public function getReason() {
        return $this->getProperty('reason');
    }
    
    public function getConstraint() {
        return $this->getProperty('constraint');
    }
    
}