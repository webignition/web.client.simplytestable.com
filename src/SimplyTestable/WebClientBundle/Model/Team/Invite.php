<?php

namespace SimplyTestable\WebClientBundle\Model\Team;

use SimplyTestable\WebClientBundle\Model\AbstractArrayBasedModel;

class Invite extends AbstractArrayBasedModel
{
    /**
     * @return string
     */
    public function getUser()
    {
        return $this->getProperty('user');
    }


    /**
     * @return string
     */
    public function getTeam()
    {
        return $this->getProperty('team');
    }


    /**
     * @return string
     */
    public function getToken()
    {
        return $this->getProperty('token');
    }
}
