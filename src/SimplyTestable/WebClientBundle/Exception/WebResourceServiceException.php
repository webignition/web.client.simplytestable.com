<?php

namespace SimplyTestable\WebClientBundle\Exception;

class WebResourceServiceException extends \Exception
{
    public function __construct($httpResponseCode)
    {
        parent::__construct('', $httpResponseCode);        
    }
}
