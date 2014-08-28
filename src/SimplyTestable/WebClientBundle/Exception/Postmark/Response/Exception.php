<?php

namespace SimplyTestable\WebClientBundle\Exception\Postmark\Response;

class Exception extends \Exception {

    const CODE_INVALID_EMAIL_ADDRESS = 300;
    const CODE_NOT_ALLOWED_TO_SEND = 405;
    const CODE_INACTIVE_RECIPIENT = 406;

    /**
     * @return bool
     */
    public function isInvalidEmailAddressException() {
        return $this->getCode() == self::CODE_INVALID_EMAIL_ADDRESS;
    }


    /**
     * @return bool
     */
    public function isNotAllowedToSendException() {
        return $this->getCode() == self::CODE_NOT_ALLOWED_TO_SEND;
    }


    /**
     * @return bool
     */
    public function isInactiveRecipientException() {
        return $this->getCode() == self::CODE_INACTIVE_RECIPIENT;
    }


}