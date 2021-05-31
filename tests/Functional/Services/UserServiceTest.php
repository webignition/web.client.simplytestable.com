<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAlreadyExistsException;
use App\Model\Coupon;
use App\Model\Team\Invite;
use App\Model\User\Summary;
use App\Services\SystemUserService;
use App\Services\UserService;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;
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
     */
    public function testResetPasswordFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->resetPassword('token', 'password');
    }

    public function resetPasswordFailureDataProvider(): array
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $notFoundResponse = HttpResponseFactory::createNotFoundResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
     */
    public function testAuthenticate(array $httpFixtures, bool $expectedAuthenticateReturnValue)
    {
        $this->userManager->setUser($this->user);
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->assertEquals($expectedAuthenticateReturnValue, $this->userService->authenticate());
        $this->assertEquals('http://null/user/user@example.com/authenticate/', $this->httpHistory->getLastRequestUrl());
    }

    public function authenticateDataProvider(): array
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
     */
    public function testCreateFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
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

    public function createFailureDataProvider(): array
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
     */
    public function testCreateSuccess(
        string $email,
        string $password,
        string $plan,
        ?Coupon $coupon,
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

    public function createSuccessDataProvider(): array
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
     */
    public function testActivateFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->activate('token-value');
    }

    public function activateFailureDataProvider(): array
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
     */
    public function testActivateAndAcceptFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
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

    public function activateAndAcceptFailureDataProvider(): array
    {
        $serviceUnavailableResponse = HttpResponseFactory::createServiceUnavailableResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
     */
    public function testExistsSuccess(
        array $httpFixtures,
        ?User $user,
        ?string $email,
        int $expectedReturnValue,
        string $expectedRequestUrl
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($user)) {
            $this->userManager->setUser($user);
        }

        $this->assertEquals($expectedReturnValue, $this->userService->exists($email));
        $this->assertEquals($expectedReturnValue, $this->userService->exists($email));
        $this->assertEquals($expectedRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    public function existsSuccessDataProvider(): array
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
     */
    public function testIsEnabled(array $httpFixtures, bool $expectedIsEnabled, string $expectedLastRequestUrl)
    {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->assertEquals($expectedIsEnabled, $this->userService->isEnabled('user@example.com'));
        $this->assertEquals($expectedLastRequestUrl, $this->httpHistory->getLastRequestUrl());
    }

    public function isEnabledDataProvider(): array
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
     */
    public function testGetSummaryFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->userService->getSummary();
    }

    public function getSummaryFailureDataProvider(): array
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
     */
    public function testGetSummarySuccess(User $user, string $expectedRequestUrl)
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

    public function getSummarySuccessDataProvider(): array
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
