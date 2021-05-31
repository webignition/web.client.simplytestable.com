<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use Mockery\MockInterface;
use App\Request\User\AbstractUserAccountRequest;

abstract class AbstractUserAccountRequestValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @return MockInterface|AbstractUserAccountRequest
     */
    protected function createUserAccountRequest(string $email, string $password)
    {
        $userAccountRequest = \Mockery::mock(AbstractUserAccountRequest::class);

        $userAccountRequest
            ->shouldReceive('getEmail')
            ->andReturn($email);

        $userAccountRequest
            ->shouldReceive('getPassword')
            ->andReturn($password);

        return $userAccountRequest;
    }

    /**
     * @return MockInterface|EmailValidator
     */
    protected function createEmailValidator(bool $isValidReturnValue)
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
