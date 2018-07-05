<?php

namespace SimplyTestable\WebClientBundle\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use SimplyTestable\WebClientBundle\Request\User\AbstractUserAccountRequest;

class UserAccountRequestValidator
{
    const STATE_EMPTY = 'empty';
    const STATE_INVALID = 'invalid';

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
     * @param AbstractUserAccountRequest $userAccountRequest
     */
    public function validate(AbstractUserAccountRequest $userAccountRequest)
    {
        $this->isValid = true;
        $email = $userAccountRequest->getEmail();

        if (empty($email)) {
            $this->invalidate(AbstractUserAccountRequest::PARAMETER_EMAIL, self::STATE_EMPTY);

            return;
        }

        if (!$this->emailValidator->isValid($email, new RFCValidation())) {
            $this->invalidate(AbstractUserAccountRequest::PARAMETER_EMAIL, self::STATE_INVALID);

            return;
        }

        $password = $userAccountRequest->getPassword();
        if (empty($password)) {
            $this->invalidate(AbstractUserAccountRequest::PARAMETER_PASSWORD, self::STATE_EMPTY);

            return;
        }
    }

    /**
     * @param string $fieldName
     * @param string $fieldState
     */
    protected function invalidate($fieldName, $fieldState)
    {
        $this->isValid = false;
        $this->invalidFieldName = $fieldName;
        $this->invalidFieldState = $fieldState;
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
