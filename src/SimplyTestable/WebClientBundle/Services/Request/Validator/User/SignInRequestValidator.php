<?php

namespace SimplyTestable\WebClientBundle\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use SimplyTestable\WebClientBundle\Request\User\SignInRequest;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use webignition\SimplyTestableUserModel\User;

class SignInRequestValidator
{
    const STATE_EMPTY = 'empty';
    const STATE_INVALID = 'invalid';
    const STATE_PUBLIC_USER = 'public-user';

    /**
     * @var EmailValidator
     */
    private $emailValidator;

    /**
     * @var bool
     */
    private $isValid;

    /**
     * @var string
     */
    private $invalidFieldName;

    /**
     * @var string
     */
    private $invalidFieldState;

    /**
     * @param EmailValidator $emailValidator
     */
    public function __construct(EmailValidator $emailValidator)
    {
        $this->emailValidator = $emailValidator;
    }

    /**
     * @param SignInRequest $signInRequest
     */
    public function validate(SignInRequest $signInRequest)
    {
        $this->isValid = true;
        $email = $signInRequest->getEmail();

        if (empty($email)) {
            $this->isValid = false;
            $this->invalidFieldName = SignInRequest::PARAMETER_EMAIL;
            $this->invalidFieldState = self::STATE_EMPTY;

            return;
        }

        if (!$this->emailValidator->isValid($email)) {
            $this->isValid = false;
            $this->invalidFieldName = SignInRequest::PARAMETER_EMAIL;
            $this->invalidFieldState = self::STATE_INVALID;

            return;
        }

        $password = $signInRequest->getPassword();
        if (empty($password)) {
            $this->isValid = false;
            $this->invalidFieldName = SignInRequest::PARAMETER_PASSWORD;
            $this->invalidFieldState = self::STATE_EMPTY;

            return;
        }

        $user = new User($email, $password);
        if (SystemUserService::isPublicUser($user)) {
            $this->isValid = false;
            $this->invalidFieldName = SignInRequest::PARAMETER_EMAIL;
            $this->invalidFieldState = self::STATE_PUBLIC_USER;

            return;
        }
    }

    /**
     * @return bool
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @return string
     */
    public function getInvalidFieldName()
    {
        return $this->invalidFieldName;
    }

    /**
     * @return string
     */
    public function getInvalidFieldState()
    {
        return $this->invalidFieldState;
    }
}
