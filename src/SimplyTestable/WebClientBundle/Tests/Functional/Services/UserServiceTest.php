<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\EntityEnclosingRequest;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\Coupon;
use SimplyTestable\WebClientBundle\Model\Team\Invite;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use Symfony\Component\HttpFoundation\Request;

class UserServiceTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->userService = $this->container->get(
            'simplytestable.services.userservice'
        );
    }

    public function testGetPublicUser()
    {
        $expectedPublicUser = new User(UserService::PUBLIC_USER_USERNAME, UserService::PUBLIC_USER_PASSWORD);

        $this->assertEquals($expectedPublicUser, $this->userService->getPublicUser());
    }

    public function testGetAdminUser()
    {
        $expectedAdminUser = new User(
            $this->container->getParameter('admin_user_username'),
            $this->container->getParameter('admin_user_password')
        );

        $this->assertEquals($expectedAdminUser, $this->userService->getAdminUser());
    }

    /**
     * @dataProvider isPublicUserDataProvider
     *
     * @param User $user
     * @param bool $expectedIsPublicUser
     */
    public function testIsPublicUser(User $user, $expectedIsPublicUser)
    {
        $isPublicUser = $this->userService->isPublicUser($user);

        $this->assertEquals($expectedIsPublicUser, $isPublicUser);
    }

    /**
     * @return array
     */
    public function isPublicUserDataProvider()
    {
        return [
            'standard public user' => [
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'expectedIsPublicUser' => true,
            ],
            'email-variant public user' => [
                'user' => new User(UserService::PUBLIC_USER_EMAIL),
                'expectedIsPublicUser' => true,
            ],
            'private user' => [
                'user' => new User('user@example.com'),
                'expectedIsPublicUser' => false,
            ],
        ];
    }

    /**
     * @dataProvider isLoggedInDataProvider
     *
     * @param User|null $user
     * @param bool $expectedIsLoggedIn
     */
    public function testIsLoggedIn($user, $expectedIsLoggedIn)
    {
        if (!empty($user)) {
            $session = $this->container->get('session');
            $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

            $session->set(UserService::SESSION_USER_KEY, $userSerializerService->serialize($user));
        }

        $this->assertEquals($expectedIsLoggedIn, $this->userService->isLoggedIn());
    }

    /**
     * @return array
     */
    public function isLoggedInDataProvider()
    {
        return [
            'no user' => [
                'user' => null,
                'expectedIsLoggedIn' => false,
            ],
            'public user' => [
                'user' => new User(UserService::PUBLIC_USER_USERNAME),
                'expectedIsLoggedIn' => false,
            ],
            'admin user' => [
                'user' => new User('admin'),
                'expectedIsLoggedIn' => false,
            ],
            'private user' => [
                'user' => new User('user@example.com'),
                'expectedIsLoggedIn' => true,
            ],
        ];
    }

    /**
     * @dataProvider resetPasswordFailureDataProvider
     *
     * @param array $httpFixtures
     * @param int $expectedReturnValue
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testResetPasswordFailure(
        array $httpFixtures,
        $expectedReturnValue
    ) {
        $this->setHttpFixtures($httpFixtures);

        $returnValue = $this->userService->resetPassword('token', 'password');

        $this->assertEquals($expectedReturnValue, $returnValue);
    }

    /**
     * @return array
     */
    public function resetPasswordFailureDataProvider()
    {
        return [
            'HTTP 404 (invalid token)' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedReturnValue' => 404,
            ],
            'HTTP 503 (down for maintenance)' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 503'),
                ],
                'expectedReturnValue' => 503,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedReturnValue' => 28,
            ],
        ];
    }

    public function testResetPasswordInvalidAdminCredentials()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $this->setExpectedException(
            CoreApplicationAdminRequestException::class,
            'Invalid admin user credentials',
            401
        );

        $this->userService->resetPassword('token', 'password');
    }

    public function testResetPasswordSuccess()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->assertTrue($this->userService->resetPassword('token', 'password value'));

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals('http://null/user/reset-password/token/', $lastRequest->getUrl());
        $this->assertEquals(
            [
                'password' => 'password%20value',
            ],
            $lastRequest->getPostFields()->toArray()
        );
    }

    public function testResetLoggedInUserPasswordSuccess()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse('token'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->userService->setUser(new User('user@example.com'));

        $this->assertTrue($this->userService->resetLoggedInUserPassword('password@value'));

        $this->assertEquals(
            [
                'http://null/user/user@example.com/token/',
                'http://null/user/reset-password/token/',
            ],
            $this->getRequestedUrls()
        );

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals('http://null/user/reset-password/token/', $lastRequest->getUrl());
        $this->assertEquals(
            [
                'password' => 'password%40value',
            ],
            $lastRequest->getPostFields()->toArray()
        );
    }

    /**
     * @dataProvider authenticateDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedAuthenticateReturnValue
     */
    public function testAuthenticate(array $httpFixtures, $expectedAuthenticateReturnValue)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->assertEquals($expectedAuthenticateReturnValue, $this->userService->authenticate());
    }

    /**
     * @return array
     */
    public function authenticateDataProvider()
    {
        return [
            'authenticated' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'expectedAuthenticateReturnValue' => true,
            ],
            'not authenticated' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedAuthenticateReturnValue' => false,
            ],
        ];
    }

    /**
     * @dataProvider createFailureDataProvider
     *
     * @param array $httpFixtures
     * @param int $expectedCreateReturnValue
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testCreateFailure(
        array $httpFixtures,
        $expectedCreateReturnValue
    ) {
        $this->setHttpFixtures($httpFixtures);

        $createReturnValue = $this->userService->create(
            'user@example.com',
            'password',
            'basic'
        );

        $this->assertEquals($expectedCreateReturnValue, $createReturnValue);
    }

    /**
     * @return array
     */
    public function createFailureDataProvider()
    {
        return [
            'user already exists' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 302'),
                ],
                'expectedCreateResponse' => 302,
            ],
            'maintenance mode' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 503'),
                ],
                'expectedCreateResponse' => 503,
            ],
            'CURL 6' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Unable to resolve host', 6)
                ],
                'expectedCreateResponse' => 6,
            ],
        ];
    }

    public function testCreateInvalidAdminCredentials()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $this->setExpectedException(
            CoreApplicationAdminRequestException::class,
            'Invalid admin user credentials',
            401
        );

        $this->userService->create(
            'user@example.com',
            'password',
            'basic'
        );
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
     * @throws CoreApplicationAdminRequestException
     */
    public function testCreateSuccess(
        $email,
        $password,
        $plan,
        $coupon,
        array $expectedPostFields
    ) {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $createReturnValue = $this->userService->create(
            $email,
            $password,
            $plan,
            $coupon
        );

        $this->assertEquals(true, $createReturnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals('http://null/user/create/', $lastRequest->getUrl());
        $this->assertEquals($expectedPostFields, $lastRequest->getPostFields()->toArray());
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
     * @param int|bool $expectedActivateReturnValue
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testActivateFailure(
        array $httpFixtures,
        $expectedActivateReturnValue
    ) {
        $this->setHttpFixtures($httpFixtures);

        $createReturnValue = $this->userService->activate('token-value');

        $this->assertEquals($expectedActivateReturnValue, $createReturnValue);
    }

    /**
     * @return array
     */
    public function activateFailureDataProvider()
    {
        return [
            'invalid token' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 400'),
                ],
                'expectedActivateReturnValue' => false,
            ],
            'maintenance mode' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 503'),
                ],
                'expectedActivateReturnValue' => 503,
            ],
            'CURL 6' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Unable to resolve host', 6)
                ],
                'expectedActivateReturnValue' => 6,
            ],
        ];
    }

    public function testActivateInvalidAdminCredentials()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $this->setExpectedException(
            CoreApplicationAdminRequestException::class,
            'Invalid admin user credentials',
            401
        );

        $this->userService->activate('token-value');
    }

    public function testActivateSuccess()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $activateReturnValue = $this->userService->activate('token-value');

        $this->assertEquals(true, $activateReturnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals('http://null/user/activate/token-value/', $lastRequest->getUrl());
    }

    /**
     * @dataProvider activateAndAcceptDataProvider
     *
     * @param array $httpFixtures
     * @param int|bool $expectedReturnValue
     * @param bool $expectedRequestIsMade
     */
    public function testActivateAndAccept(
        array $httpFixtures,
        $expectedReturnValue,
        $expectedRequestIsMade
    ) {
        $this->setHttpFixtures($httpFixtures);

        $invite = new Invite([
            'token' => 'token-value',
        ]);

        $returnValue = $this->userService->activateAndAccept($invite, 'password-value');

        $this->assertEquals($expectedReturnValue, $returnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        if (!$expectedRequestIsMade) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals('http://null/team/invite/activate/accept/', $lastRequest->getUrl());
            $this->assertEquals(
                [
                    'token' => 'token-value',
                    'password' => 'password-value',
                ],
                $lastRequest->getPostFields()->toArray()
            );
        }
    }

    /**
     * @return array
     */
    public function activateAndAcceptDataProvider()
    {
        return [
            'HTTP 400' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 400'),
                ],
                'expectedReturnValue' => 400,
                'expectedRequestIsMade' => true,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedReturnValue' => 28,
                'expectedRequestIsMade' => false,
            ],
            'Success' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'expectedReturnValue' => true,
                'expectedRequestIsMade' => true,
            ],
        ];
    }

    public function testExistsInvalidAdminCredentials()
    {
        $this->setHttpFixtures([
            Response::fromMessage('HTTP/1.1 401'),
        ]);

        $this->setExpectedException(
            CoreApplicationAdminRequestException::class,
            'Invalid admin user credentials',
            401
        );

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
     * @throws CoreApplicationAdminRequestException
     */
    public function testExistsSuccess(
        array $httpFixtures,
        $user,
        $email,
        $expectedReturnValue,
        $expectedRequestUrl
    ) {
        $this->setHttpFixtures($httpFixtures);

        if (!empty($user)) {
            $this->userService->setUser($user);
        }

        $returnValue = $this->userService->exists($email);

        $this->assertEquals($expectedReturnValue, $returnValue);

        /* @var EntityEnclosingRequest $lastRequest */
        $lastRequest = $this->getLastRequest();

        $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
    }

    /**
     * @return array
     */
    public function existsSuccessDataProvider()
    {
        return [
            'not exists' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'user' => null,
                'userEmail' => 'user@example.com',
                'expectedReturnValue' => false,
                'expectedRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'exists' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'user' => null,
                'userEmail' => 'user@example.com',
                'expectedReturnValue' => true,
                'expectedRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'exists; user is set' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
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
     * @throws CoreApplicationAdminRequestException
     */
    public function testIsEnabled(array $httpFixtures, $expectedIsEnabled, $expectedLastRequestUrl)
    {
        $this->setHttpFixtures($httpFixtures);

        $this->assertEquals($expectedIsEnabled, $this->userService->isEnabled('user@example.com'));
        $this->assertEquals($expectedLastRequestUrl, $this->getLastRequest()->getUrl());
    }

    /**
     * @return array
     */
    public function isEnabledDataProvider()
    {
        return [
            'not enabled; does not exist' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedIsEnabled' => false,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/exists/',
            ],
            'not enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedIsEnabled' => false,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/enabled/',
            ],
            'enabled' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 200'),
                    Response::fromMessage('HTTP/1.1 200'),
                ],
                'expectedIsEnabled' => true,
                'expectedLastRequestUrl' => 'http://null/user/user@example.com/enabled/',
            ],
        ];
    }

    public function testGetConfirmationToken()
    {
        $token = 'token-value';

        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse($token),
        ]);

        $retrievedToken = $this->userService->getConfirmationToken('user@example.com');

        $this->assertEquals($token, $retrievedToken);
        $this->assertEquals('http://null/user/user@example.com/token/', $this->getLastRequest()->getUrl());
    }

    public function testSetUser()
    {
        $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
        $session = $this->container->get('session');

        $user = new User('user@example.com', 'password-value');
        $serializedUser = $userSerializerService->serialize($user);

        $this->userService->setUser($user);

        $this->assertEquals($serializedUser, $session->get(UserService::SESSION_USER_KEY));
        $this->assertEquals($user, $this->userService->getUser());
    }

    /**
     * @dataProvider setUserFromRequestDataProvider
     *
     * @param Request $request
     * @param User $expectedUser
     */
    public function testSetUserFromRequest(Request $request, User $expectedUser)
    {
        if ($request->cookies->has(UserService::USER_COOKIE_KEY)) {
            $cookieUser = $request->cookies->get(UserService::USER_COOKIE_KEY);

            if ($cookieUser instanceof User) {
                $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');
                $request->cookies->set(
                    UserService::USER_COOKIE_KEY,
                    $userSerializerService->serializeToString($cookieUser)
                );
            }
        }

        $this->userService->setUserFromRequest($request);

        $this->assertEquals($expectedUser, $this->userService->getUser());
    }

    /**
     * @return array
     */
    public function setUserFromRequestDataProvider()
    {
        $publicUser = new User(UserService::PUBLIC_USER_USERNAME, UserService::PUBLIC_USER_PASSWORD);
        $privateUser = new User('user@example.com');

        return [
            'no user in request cookie' => [
                'request' => new Request(),
                'expectedUser' => $publicUser,
            ],
            'invalid user in request cookie' => [
                'request' => new Request([], [], [], [
                    UserService::USER_COOKIE_KEY => 'foo',
                ]),
                'expectedUser' => $publicUser,
            ],
            'valid user in request cookie' => [
                'request' => new Request([], [], [], [
                    UserService::USER_COOKIE_KEY => $privateUser,
                ]),
                'expectedUser' => $privateUser,
            ],
        ];
    }

    /**
     * @dataProvider getUserDataProvider
     *
     * @param User|null $sessionUser
     * @param User $expectedUser
     */
    public function testGetUser($sessionUser, User $expectedUser)
    {
        if (!empty($sessionUser)) {
            $session = $this->container->get('session');
            $userSerializerService = $this->container->get('simplytestable.services.userserializerservice');

            $session->set(UserService::SESSION_USER_KEY, $userSerializerService->serialize($sessionUser));
        }

        $user = $this->userService->getUser();

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @return array
     */
    public function getUserDataProvider()
    {
        $publicUser = new User(UserService::PUBLIC_USER_USERNAME, UserService::PUBLIC_USER_PASSWORD);
        $publicUserEmailVariant = new User(UserService::PUBLIC_USER_EMAIL, UserService::PUBLIC_USER_PASSWORD);
        $privateUser = new User('user@example.com');

        return [
            'no user in session' => [
                'sessionUser' => null,
                'expectedUser' => $publicUser
            ],
            'has public user in session' => [
                'sessionUser' => $publicUser,
                'expectedUser' => $publicUser
            ],
            'has public user email variant in session' => [
                'sessionUser' => $publicUserEmailVariant,
                'expectedUser' => $publicUser
            ],
            'has private user in session' => [
                'sessionUser' => $privateUser,
                'expectedUser' => $privateUser
            ],
        ];
    }

    public function testClearUser()
    {
        $session = $this->container->get('session');
        $user = 'foo';

        $session->set(UserService::SESSION_USER_KEY, $user);

        $this->assertEquals($user, $session->get(UserService::SESSION_USER_KEY));

        $this->userService->clearUser();

        $this->assertNull($session->get(UserService::SESSION_USER_KEY));
    }

    /**
     * @dataProvider getSummaryFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param int $expectedExceptionCode
     *
     * @throws WebResourceException
     */
    public function testGetSummaryFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);

        $this->userService->getSummary();
    }

    /**
     * @return array
     */
    public function getSummaryFailureDataProvider()
    {
        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedException' => WebResourceException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedException' => CurlException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    /**
     * @dataProvider getSummarySuccessDataProvider
     *
     * @param User|null $user
     * @param string $expectedRequestUrl
     *
     * @throws WebResourceException
     */
    public function testGetSummarySuccess($user, $expectedRequestUrl)
    {
        $userService = $this->container->get('simplytestable.services.userservice');

        if (!empty($user)) {
            $this->userService->setUser($user);
        }

        $this->setHttpFixtures([
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

        $userSummary = $userService->getSummary();

        $this->assertInstanceOf(User\Summary::class, $userSummary);
        $this->assertEquals($expectedRequestUrl, $this->getLastRequest()->getUrl());
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
                'user' => null,
                'expectedRequestUrl' => 'http://null/user/public/',
            ],
        ];
    }
}
