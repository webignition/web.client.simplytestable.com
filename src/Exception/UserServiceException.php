<?php

namespace App\Exception;

class UserServiceException extends \Exception
{
    public function __construct($httpResponseCode)
    {
        parent::__construct('', $httpResponseCode);
    }
}
