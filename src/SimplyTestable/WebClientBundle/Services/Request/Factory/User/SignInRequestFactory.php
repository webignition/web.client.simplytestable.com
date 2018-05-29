<?php

namespace SimplyTestable\WebClientBundle\Services\Request\Factory\User;

use SimplyTestable\WebClientBundle\Request\User\SignInRequest;
use SimplyTestable\WebClientBundle\Services\Request\Factory\AbstractRequestFactory;

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
