<?php
namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\Object;

class Team extends Object {

    /**
     *
     * @param \stdClass $data
     */
    public function __construct($data) {
        parent::__construct($data);
        if (is_null($this->getDataProperty('people'))) {
            $this->setDataProperty('people', [
                $this->getLeader()
            ]);
        }

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


    /**
     * @return string[]
     */
    public function getMembers() {
        return $this->getDataProperty('members');
    }


    /**
     * @return bool
     */
    public function hasMembers() {
        return count($this->getMembers()) > 0;
    }

}