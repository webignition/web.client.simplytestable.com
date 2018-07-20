<?php

namespace Tests\AppBundle\Functional\Services;

use AppBundle\Exception\CoreApplicationReadOnlyException;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Exception\UserAccountCardException;
use AppBundle\Services\UserAccountCardService;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;
use webignition\SimplyTestableUserModel\User;

class UserAccountCardServiceTest extends AbstractCoreApplicationServiceTest
{
    const STRIPE_CARD_TOKEN = 'tok_Bb4A2szGLfgwJe';

    /**
     * @var UserAccountCardService
     */
    private $userAccountCardService;

    /**
     * @var User
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userAccountCardService = self::$container->get(UserAccountCardService::class);

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider associateStripeErrorDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedExceptionParam
     * @param string $expectedExceptionStripeCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testAssociateStripeError(
        array $httpFixtures,
        $expectedExceptionParam,
        $expectedExceptionStripeCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        try {
            $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
            $this->fail(UserAccountCardException::class. ' not thrown');
        } catch (UserAccountCardException $userAccountCardException) {
            $this->assertEquals($expectedExceptionParam, $userAccountCardException->getParam());
            $this->assertEquals($expectedExceptionStripeCode, $userAccountCardException->getStripeCode());
        }
    }

    /**
     * @return array
     */
    public function associateStripeErrorDataProvider()
    {
        return [
            'invalid address zip' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-Stripe-Error-Message' => 'The zip code you supplied failed validation.',
                        'X-Stripe-Error-Param' => 'address_zip',
                        'X-Stripe-Error-Code' => 'incorrect_zip'
                    ]),
                ],
                'expectedExceptionParam' => 'address_zip',
                'expectedExceptionStripeCode' => 'incorrect_zip',
            ],
        ];
    }

    /**
     * @dataProvider associateFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws UserAccountCardException
     */
    public function testAssociateFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);
    }

    /**
     * @return array
     */
    public function associateFailureDataProvider()
    {
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 400' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Bad Request',
                'expectedExceptionCode' => 400,
            ],
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testAssociateSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userAccountCardService->associate($this->user, self::STRIPE_CARD_TOKEN);

        $this->assertEquals(
            'http://null/user/user@example.com/card/tok_Bb4A2szGLfgwJe/associate/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
