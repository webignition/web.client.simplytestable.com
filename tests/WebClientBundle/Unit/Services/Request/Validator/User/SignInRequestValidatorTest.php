<?php

namespace Tests\WebClientBundle\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Request\User\SignInRequest;
use SimplyTestable\WebClientBundle\Services\Request\Validator\User\SignInRequestValidator;

class SignInRequestValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validateDataProvider
     *
     * @param EmailValidator $emailValidator
     * @param SignInRequest $signInRequest
     * @param bool $expectedIsValid
     * @param string|null $expectedInvalidFieldName
     * @param string|null $expectedInvalidFieldState
     */
    public function testValidate(
        EmailValidator $emailValidator,
        SignInRequest $signInRequest,
        $expectedIsValid,
        $expectedInvalidFieldName,
        $expectedInvalidFieldState
    ) {
        $signInRequestValidator = new SignInRequestValidator($emailValidator);
        $signInRequestValidator->validate($signInRequest);

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
            'empty email' => [
                'emailValidator' => $this->createEmailValidator(false),
                'signInRequest' => new SignInRequest('', '', '', false),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => SignInRequest::PARAMETER_EMAIL,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_EMPTY,
            ],
            'invalid email' => [
                'emailValidator' => $this->createEmailValidator(false),
                'signInRequest' => new SignInRequest('foo', '', '', false),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => SignInRequest::PARAMETER_EMAIL,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_INVALID,
            ],
            'empty password' => [
                'emailValidator' => $this->createEmailValidator(true),
                'signInRequest' => new SignInRequest('user@example.com', '', '', false),
                'expectedIsValid' => false,
                'expectedInvalidFieldName' => SignInRequest::PARAMETER_PASSWORD,
                'expectedInvalidFieldState' => SignInRequestValidator::STATE_EMPTY,
            ],
            'valid' => [
                'emailValidator' => $this->createEmailValidator(true),
                'signInRequest' => new SignInRequest('user@example.com', 'password value', '', false),
                'expectedIsValid' => true,
                'expectedInvalidFieldName' => null,
                'expectedInvalidFieldState' => null,
            ],
        ];
    }

    /**
     * @param bool $isValidReturnValue
     *
     * @return MockInterface|EmailValidator
     */
    private function createEmailValidator($isValidReturnValue)
    {
        $emailValidator = \Mockery::mock(EmailValidator::class);
        $emailValidator
            ->shouldReceive('isValid')
            ->andReturn($isValidReturnValue);

        return $emailValidator;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
