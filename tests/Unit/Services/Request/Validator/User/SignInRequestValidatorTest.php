<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use App\Request\User\AbstractUserAccountRequest;
use App\Request\User\AbstractUserRequest;
use App\Services\Request\Validator\User\SignInRequestValidator;

class SignInRequestValidatorTest extends AbstractUserAccountRequestValidatorTest
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(
        EmailValidator $emailValidator,
        AbstractUserAccountRequest $userAccountRequest,
        bool $expectedIsValid,
        string $expectedInvalidFieldName,
        string $expectedInvalidFieldState
    ) {
        $signInRequestValidator = new SignInRequestValidator($emailValidator);
        $signInRequestValidator->validate($userAccountRequest);

        $this->assertEquals($expectedIsValid, $signInRequestValidator->getIsValid());
        $this->assertEquals($expectedInvalidFieldName, $signInRequestValidator->getInvalidFieldName());
        $this->assertEquals($expectedInvalidFieldState, $signInRequestValidator->getInvalidFieldState());
    }

    public function validateDataProvider(): array
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
