<?php

namespace App\Services\Request\Factory\User;

use App\Request\User\SignInRequest;
use App\Services\Request\Factory\AbstractRequestFactory;

class SignInRequestFactory extends AbstractRequestFactory
{
    /**
     * @return SignInRequest
     */
    public function create()
    {
        return new SignInRequest(
            $this->getStringValueFromRequestParameters(SignInRequest::PARAMETER_EMAIL),
            $this->getStringValueFromRequestParameters(SignInRequest::PARAMETER_PASSWORD),
            $this->getStringValueFromRequestParameters(SignInRequest::PARAMETER_REDIRECT),
            $this->getBooleanValueFromRequestParameters(SignInRequest::PARAMETER_STAY_SIGNED_IN)
        );
    }
}
