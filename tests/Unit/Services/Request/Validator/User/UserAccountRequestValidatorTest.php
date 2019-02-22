<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use App\Request\User\AbstractUserAccountRequest;
use App\Request\User\AbstractUserRequest;
use App\Services\Request\Validator\User\SignInRequestValidator;
use App\Services\Request\Validator\User\UserAccountRequestValidator;

class UserAccountRequestValidatorTest extends AbstractUserAccountRequestValidatorTest
{
    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(
        EmailValidator $emailValidator,
        AbstractUserAccountRequest $userAccountRequest,
        bool $expectedIsValid,
        ?string $expectedInvalidFieldName,
        ?string $expectedInvalidFieldState
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
            'empty email' => [
                'emailValidator' => $this->createEmailValidator(false),
                'userAccountRequest' => $this->createUserAccountRequest('', ''),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => AbstractUserRequest::PARAMETER_EMAIL,
                'expectedInvalidFieldState' => UserAccountRequestValidator::STATE_EMPTY,
            ],
            'invalid email' => [
                'emailValidator' => $this->createEmailValidator(false),
                'userAccountRequest' => $this->createUserAccountRequest('foo', ''),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => AbstractUserRequest::PARAMETER_EMAIL,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_INVALID,
            ],
            'empty password' => [
                'emailValidator' => $this->createEmailValidator(true),
                'userAccountRequest' => $this->createUserAccountRequest('user@example.com', ''),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => AbstractUserAccountRequest::PARAMETER_PASSWORD,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_EMPTY,
            ],
            'valid' => [
                'emailValidator' => $this->createEmailValidator(true),
                'userAccountRequest' => $this->createUserAccountRequest('user@example.com', 'password value'),
                'expectedIsValid' => true,
                'expectedInvalidFieldName' => null,
                'expectedInvalidFieldState' => null,
            ],
        ];
    }
}
