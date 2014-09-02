<?php

namespace SimplyTestable\WebClientBundle\Exception;

class CoreApplicationAdminRequestException extends \Exception {

    const CODE_INVALID_CREDENTIALS = 401;

    /**
     * @return bool
     */
    public function isInvalidCredentialsException() {
        return $this->getCode() == self::CODE_INVALID_CREDENTIALS;
    }

}
