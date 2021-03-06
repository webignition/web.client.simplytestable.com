<?php

namespace App\Services\Request\Validator\User;

use App\Request\User\AbstractUserAccountRequest;
use App\Request\User\SignInRequest;
use App\Services\SystemUserService;
use webignition\SimplyTestableUserModel\User;

class SignInRequestValidator extends UserAccountRequestValidator
{
    const STATE_PUBLIC_USER = 'public-user';

    /**
     * @param AbstractUserAccountRequest $userAccountRequest
     */
    public function validate(AbstractUserAccountRequest $userAccountRequest)
    {
        parent::validate($userAccountRequest);

        if (!$this->getIsValid()) {
            return;
        }

        $email = $userAccountRequest->getEmail();
        $password = $userAccountRequest->getPassword();

        $user = new User($email, $password);
        if (SystemUserService::isPublicUser($user)) {
            $this->invalidate(SignInRequest::PARAMETER_EMAIL, self::STATE_PUBLIC_USER);

            return;
        }
    }
}
