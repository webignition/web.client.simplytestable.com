<?php

namespace Tests\AppBundle\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use Mockery\MockInterface;
use App\Request\User\AbstractUserAccountRequest;

abstract class AbstractUserAccountRequestValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @param string $email
     * @param string $password
     *
     * @return MockInterface|AbstractUserAccountRequest
     */
    protected function createUserAccountRequest($email, $password)
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
     * @param bool $isValidReturnValue
     *
     * @return MockInterface|EmailValidator
     */
    protected function createEmailValidator($isValidReturnValue)
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
