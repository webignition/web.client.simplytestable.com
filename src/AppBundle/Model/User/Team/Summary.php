<?php

namespace AppBundle\Model\User\Team;

use AppBundle\Model\AbstractArrayBasedModel;

class Summary extends AbstractArrayBasedModel
{
    /**
     * @return bool
     */
    public function isInTeam()
    {
        return $this->getProperty('in');
    }

    /**
     * @return bool
     */
    public function hasInvite()
    {
        return $this->getProperty('has_invite');
    }
}
