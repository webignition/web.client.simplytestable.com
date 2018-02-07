<?php

namespace SimplyTestable\WebClientBundle\Exception;

class UserEmailChangeException extends \Exception
{
    const CODE_UNKNOWN = 0;
    const MESSAGE_UNKNOWN = 'An unknown error occurred';

    const CODE_EMAIL_ALREADY_TAKEN = 1;
    const MESSAGE_EMAIL_ALREADY_TAKEN = 'Email address %s already taken';

    /**
     * @return bool
     */
    public function isEmailAddressAlreadyTakenException()
    {
        return self::CODE_EMAIL_ALREADY_TAKEN === $this->getCode();
    }
}
