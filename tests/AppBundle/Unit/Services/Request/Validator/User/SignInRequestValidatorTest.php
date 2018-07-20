<?php

namespace Tests\AppBundle\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use AppBundle\Request\User\AbstractUserAccountRequest;
use AppBundle\Request\User\AbstractUserRequest;
use AppBundle\Services\Request\Validator\User\SignInRequestValidator;

class SignInRequestValidatorTest extends AbstractUserAccountRequestValidatorTest
{
    /**
     * @dataProvider validateDataProvider
     *
     * @param EmailValidator $emailValidator
     * @param AbstractUserAccountRequest $userAccountRequest,
     * @param bool $expectedIsValid
     * @param string|null $expectedInvalidFieldName
     * @param string|null $expectedInvalidFieldState
     */
    public function testValidate(
        EmailValidator $emailValidator,
        AbstractUserAccountRequest $userAccountRequest,
        $expectedIsValid,
        $expectedInvalidFieldName,
        $expectedInvalidFieldState
    ) {
        $signInRequestValidator = new SignInRequestValidator($emailValidator);
        $signInRequestValidator->validate($userAccountRequest);

        $this->assertEquals($expectedIsValid, $signInRequestValidator->getIsValid());
        $this->assertEquals($expectedInvalidFieldName, $signInRequestValidator->getInvalidFieldName());
        $this->assertEquals($expectedInvalidFieldState, $signInRequestValidator->getInvalidFieldState());
    }

    /**
     * @return array
     */
    public function validateDataProvider()
    {
        return [
            'public user' => [
                'emailValidator' => $this->createEmailValidator(true),
                'signInRequest' => $this->createUserAccountRequest('public@simplytestable.com', 'foo'),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => AbstractUserRequest::PARAMETER_EMAIL,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_PUBLIC_USER,
            ],
        ];
    }
}
