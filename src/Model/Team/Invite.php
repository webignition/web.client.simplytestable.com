<?php

namespace App\Model\Team;

use App\Model\AbstractArrayBasedModel;

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
