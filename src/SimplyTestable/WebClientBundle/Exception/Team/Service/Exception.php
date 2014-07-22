<?php

namespace SimplyTestable\WebClientBundle\Exception\Team\Service;

use \Exception as BaseException;

class Exception extends BaseException {

    const INVITEE_IS_A_TEAM_LEADER = 2;
    const USER_IS_ALREADY_ON_A_TEAM = 3;


    /**
     * @return bool
     */
    public function isInviteeIsATeamLeaderException() {
        return $this->getCode() == self::INVITEE_IS_A_TEAM_LEADER;
    }


    /**
     * @return bool
     */
    public function isUserIsAlreadyOnATeamException() {
        return $this->getCode() == self::USER_IS_ALREADY_ON_A_TEAM;
    }


}