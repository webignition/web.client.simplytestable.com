<?php

namespace App\Services\Request\Factory\User;

use App\Request\User\SignUpRequest;
use App\Services\Request\Factory\AbstractRequestFactory;

class SignUpRequestFactory extends AbstractRequestFactory
{
    /**
     * @return SignUpRequest
     */
    public function create()
    {
        return new SignUpRequest(
            $this->getStringValueFromRequestParameters(SignUpRequest::PARAMETER_EMAIL),
            $this->getStringValueFromRequestParameters(SignUpRequest::PARAMETER_PASSWORD),
            $this->getStringValueFromRequestParameters(SignUpRequest::PARAMETER_PLAN)
        );
    }
}
