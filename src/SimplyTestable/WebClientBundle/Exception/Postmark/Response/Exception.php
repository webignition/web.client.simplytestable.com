<?php

namespace SimplyTestable\WebClientBundle\Exception\Postmark\Response;

class Exception extends \Exception {

    const CODE_INVALID_EMAIL_ADDRESS = 300;

    /**
     * @return bool
     */
    public function isInvalidEmailAddressException() {
        return $this->getCode() == self::CODE_INVALID_EMAIL_ADDRESS;
    }

}