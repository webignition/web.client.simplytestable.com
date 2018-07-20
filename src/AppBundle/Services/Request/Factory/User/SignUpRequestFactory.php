<?php

namespace AppBundle\Services\Request\Factory\User;

use AppBundle\Request\User\SignUpRequest;
use AppBundle\Services\Request\Factory\AbstractRequestFactory;

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
