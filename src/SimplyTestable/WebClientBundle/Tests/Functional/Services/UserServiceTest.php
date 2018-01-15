<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

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

            $session->set('user', $userSerializerService->serialize($user));
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
     * @param string$expectedRequestUrl
     *
     * @throws CoreApplicationAdminRequestException
     */
    public function testResetPasswordFailure(
        array $httpFixtures,
        $expectedReturnValue,
        $expectedRequestUrl
    ) {
        $this->setHttpFixtures($httpFixtures);

        $returnValue = $this->userService->resetPassword('token', 'password');

        $this->assertEquals($expectedReturnValue, $returnValue);

        if (empty($expectedRequestUrl)) {
            $this->assertNull($this->getLastRequest());
        } else {
            $this->assertEquals(
                $expectedRequestUrl,
                $this->getLastRequest()->getUrl()
            );
        }
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
                'expectedRequestUrl' => 'http://null/user/reset-password/token/',
            ],
            'HTTP 503 (down for maintenance)' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 503'),
                ],
                'expectedReturnValue' => 503,
                'expectedRequestUrl' => 'http://null/user/reset-password/token/',
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedReturnValue' => 28,
                'expectedRequestUrl' => null,
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

        $this->assertTrue($this->userService->resetPassword('token', 'password'));

        $this->assertEquals(
            'http://null/user/reset-password/token/',
            $this->getLastRequest()->getUrl()
        );
    }

    public function testResetLoggedInUserPasswordSuccess()
    {
        $this->setHttpFixtures([
            HttpResponseFactory::createJsonResponse('token'),
            Response::fromMessage('HTTP/1.1 200'),
        ]);

        $this->userService->setUser(new User('user@example.com'));

        $this->assertTrue($this->userService->resetLoggedInUserPassword('password'));

        $this->assertEquals(
            [
                'http://null/user/user@example.com/token/',
                'http://null/user/reset-password/token/',
            ],
            $this->getRequestedUrls()
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
}
