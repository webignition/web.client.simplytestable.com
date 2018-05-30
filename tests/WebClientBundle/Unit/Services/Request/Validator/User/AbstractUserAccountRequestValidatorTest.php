<?php

namespace Tests\WebClientBundle\Unit\Services\Request\Validator\User;

use Egulias\EmailValidator\EmailValidator;
use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Request\User\AbstractUserAccountRequest;

abstract class AbstractUserAccountRequestValidatorTest extends \PHPUnit_Framework_TestCase
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