<?php

namespace SimplyTestable\WebClientBundle\Exception\Team\Service;

use \Exception as BaseException;

class Exception extends BaseException {

    const INVITEE_IS_A_TEAM_LEADER = 2;


    /**
     * @return bool
     */
    public function isInviteeIsATeamLeaderException() {
        return $this->getCode() == self::INVITEE_IS_A_TEAM_LEADER;
    }


}