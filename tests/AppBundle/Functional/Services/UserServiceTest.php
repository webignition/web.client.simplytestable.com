<?php

namespace Tests\AppBundle\Functional\Services;

use AppBundle\Exception\CoreApplicationReadOnlyException;
use AppBundle\Exception\CoreApplicationRequestException;
use AppBundle\Exception\InvalidAdminCredentialsException;
use AppBundle\Exception\InvalidContentTypeException;
use AppBundle\Exception\InvalidCredentialsException;
use AppBundle\Exception\UserAlreadyExistsException;
use AppBundle\Model\Coupon;
use AppBundle\Model\Team\Invite;
use AppBundle\Model\User\Summary;
use AppBundle\Services\SystemUserService;
use AppBundle\Services\UserService;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;
use webignition\SimplyTestableUserModel\User;

class UserServiceTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var UserService
     */
    private $userService;

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

        $this->userService = self::$container->get(UserService::class);

        $this->user = new User('user@example.com');
    }

    /**
     * @dataProvider resetPasswordFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testResetPasswordFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->resetPassword('token', 'password');
    }

    /**
     * @return array
     */
    public function resetPasswordFailureDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $notFoundResponse = HttpResponseFactory::createNotFoundResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 404 (invalid token)' => [
                'httpFixtures' => [
                    $notFoundResponse,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'read only' => [
                'httpFixtures' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'invalid credentials' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedException' => InvalidCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
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

    public function testResetPasswordSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userService->resetPassword('token', 'password value');

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals('http://null/user/reset-password/token/', $lastRequest->getUri());
        $this->assertEquals(['password' => 'password%20value'], $postedData);
    }

    /**
     * @dataProvider authenticateDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedAuthenticateReturnValue
     */
    public function testAuthenticate(array $httpFixtures, $expectedAuthenticateReturnValue)
    {
        $this->userManager->setUser($this->user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->assertEquals($expectedAuthenticateReturnValue, $this->userService->authenticate());
        $this->assertEquals('http://null/user/user@example.com/authenticate/', $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function authenticateDataProvider()
    {
        return [
            'authenticated' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'expectedAuthenticateReturnValue' => true,
            ],
            'not authenticated; not found' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedAuthenticateReturnValue' => false,
            ],
            'not authenticated; invalid credentials' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedAuthenticateReturnValue' => false,
            ],
        ];
    }

    /**
     * @dataProvider createFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws UserAlreadyExistsException
     */
    public function testCreateFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->create(
            'user@example.com',
            'password',
            'basic'
        );
    }

    /**
     * @return array
     */
    public function createFailureDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'user already exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createRedirectResponse(),
                ],
                'expectedException' => UserAlreadyExistsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'maintenance mode' => [
                'httpFixtures' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
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

    /**
     * @dataProvider createSuccessDataProvider
     *
     * @param string $email
     * @param string $password
     * @param string $plan
     * @param string|null $coupon
     * @param array $expectedPostFields
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws UserAlreadyExistsException
     */
    public function testCreateSuccess(
        $email,
        $password,
        $plan,
        $coupon,
        array $expectedPostFields
    ) {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userService->create(
            $email,
            $password,
            $plan,
            $coupon
        );

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals('http://null/user/create/', (string)$lastRequest->getUri());
        $this->assertEquals($expectedPostFields, $postedData);
    }

    /**
     * @return array
     */
    public function createSuccessDataProvider()
    {
        $coupon = new Coupon();
        $coupon->setCode('coupon-code');

        return [
            'no coupon' => [
                'email' => 'user@example.com',
                'password' => 'password-value',
                'plan' => 'basic',
                'coupon' => null,
                'expectedPostFields' => [
                    'email' => 'user%40example.com',
                    'password' => 'password-value',
                    'plan' => 'basic',
                ],
            ],
            'with coupon' => [
                'email' => 'user@example.com',
                'password' => 'password-value',
                'plan' => 'basic',
                'coupon' => $coupon,
                'expectedPostFields' => [
                    'email' => 'user%40example.com',
                    'password' => 'password-value',
                    'plan' => 'basic',
                    'coupon' => 'coupon-code',
                ],
            ],
        ];
    }

    /**
     * @dataProvider activateFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     */
    public function testActivateFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->activate('token-value');
    }

    /**
     * @return array
     */
    public function activateFailureDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'invalid token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Bad Request',
                'expectedExceptionCode' => 400,
            ],
            'invalid admin credentials' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'expectedException' => InvalidAdminCredentialsException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
            'maintenance mode' => [
                'httpFixtures' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
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

    public function testActivateSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $this->userService->activate('token-value');
        $this->assertEquals('http://null/user/activate/token-value/', $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @dataProvider activateAndAcceptFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testActivateAndAcceptFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $invite = new Invite([
            'token' => 'token-value',
        ]);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->activateAndAccept($invite, 'password-value');
    }

    /**
     * @return array
     */
    public function activateAndAcceptFailureDataProvider()
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'no invite for token' => [
                'httpFixtures' => [
                    HttpResponseFactory::createBadRequestResponse([
                        'X-TeamInviteActivateAndAccept-Error-Code' => 1,
                        'X-TeamInviteActivateAndAccept-Error-Message' => 'No invite for token',
                    ]),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Bad Request',
                'expectedExceptionCode' => 400,
            ],
            'maintenance mode' => [
                'httpFixtures' => [
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                    $serviceUnavailableResponse,
                ],
                'expectedException' => CoreApplicationReadOnlyException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
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

    public function testActivateAndAcceptSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $invite = new Invite([
            'token' => 'token-value',
        ]);

        $this->userService->activateAndAccept($invite, 'password-value');

        $lastRequest = $this->httpHistory->getLastRequest();

        $postedData = [];
        parse_str($lastRequest->getBody()->getContents(), $postedData);

        $this->assertEquals('http://null/team/invite/activate/accept/', (string)$lastRequest->getUri());
        $this->assertEquals(
            [
                'token' => 'token-value',
                'password' => 'password-value',
            ],
            $postedData
        );
    }

    public function testExistsInvalidAdminCredentials()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createForbiddenResponse(),
        ]);

        $this->expectException(InvalidAdminCredentialsException::class);

        $this->userService->exists('user@example.com');
    }

    /**
     * @dataProvider existsSuccessDataProvider
     *
     * @param array $httpFixtures
     * @param User|null $user
     * @param string $email
     * @param int|bool $expectedReturnValue
     * @param string $expectedRequestUrl
     *
     * @throws InvalidAdminCredentialsException
     */
    public function testExistsSuccess(
        array $httpFixtures,
        $user,
        $email,
        $expectedReturnValue,
        $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($user)) {
            $this->userManager->setUser($user);
        }

        $this->assertEquals($expectedReturnValue, $this->userService->exists($email));
        $this->assertEquals($expectedReturnValue, $this->userService->exists($email));
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function existsSuccessDataProvider()
    {
        return [
            'not exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'user' => null,
                'userEmail' => 'user@example.com',
                'expectedReturnValue' => false,
                'expectedRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'exists' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => null,
                'userEmail' => 'user@example.com',
                'expectedReturnValue' => true,
                'expectedRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'exists; user is set' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse(),
                ],
                'user' => new User('user-foo@example.com'),
                'userEmail' => null,
                'expectedReturnValue' => true,
                'expectedRequestUrl' => 'http://null/user/user-foo@example.com/exists/',
            ],
        ];
    }

    /**
     * @dataProvider isEnabledDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedIsEnabled
     * @param string$expectedLastRequestUrl
     *
     * @throws InvalidAdminCredentialsException
     */
    public function testIsEnabled(array $httpFixtures, $expectedIsEnabled, $expectedLastRequestUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->assertEquals($expectedIsEnabled, $this->userService->isEnabled('user@example.com'));
        $this->assertEquals($expectedLastRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function isEnabledDataProvider()
    {
        $notFoundResponse = HttpResponseFactory::createNotFoundResponse();
        $successResponse = HttpResponseFactory::createSuccessResponse();

        return [
            'not enabled; does not exist' => [
                'httpFixtures' => [
                    $notFoundResponse,
                ],
                'expectedIsEnabled' => false,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'not enabled' => [
                'httpFixtures' => [
                    $successResponse,
                    $notFoundResponse,
                ],
                'expectedIsEnabled' => false,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/enabled/',
            ],
            'enabled' => [
                'httpFixtures' => [
                    $successResponse,
                    $successResponse,
                ],
                'expectedIsEnabled' => true,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/enabled/',
            ],
        ];
    }

    public function testGetConfirmationTokenSuccess()
    {
        $token = 'token-value';

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse($token),
        ]);

        $retrievedToken = $this->userService->getConfirmationToken('user@example.com');

        $this->assertEquals($token, $retrievedToken);
        $this->assertEquals('http://null/user/user@example.com/token/', $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @dataProvider getSummaryFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function testGetSummaryFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->getSummary();
    }

    /**
     * @return array
     */
    public function getSummaryFailureDataProvider()
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
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

    /**
     * @dataProvider getSummarySuccessDataProvider
     *
     * @param User|null $user
     * @param string $expectedRequestUrl
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function testGetSummarySuccess($user, $expectedRequestUrl)
    {
        $this->userManager->setUser($user);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'email' => 'basic-not-in-team@example.com',
                'user_plan' => [
                    'plan' => [
                        'name' => 'basic',
                        'is_premium' => false,
                    ],
                    'start_trial_period' => 30,
                ],
                'plan_constraints' => [
                    'credits' => [
                        'limit' => 500,
                        'used' => 0,
                    ],
                    'urls_per_job' => 10,
                ],
                'team_summary' => [
                    'in' => false,
                    'has_invite' => false,
                ],
            ]),
        ]);

        $userSummary = $this->userService->getSummary();

        $this->assertInstanceOf(Summary::class, $userSummary);
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @return array
     */
    public function getSummarySuccessDataProvider()
    {
        return [
            'has user' => [
                'user' => new User('user@example.com'),
                'expectedRequestUrl' => 'http://null/user/user@example.com/',
            ],
            'no user' => [
                'user' => SystemUserService::getPublicUser(),
                'expectedRequestUrl' => 'http://null/user/public/',
            ],
        ];
    }
}
