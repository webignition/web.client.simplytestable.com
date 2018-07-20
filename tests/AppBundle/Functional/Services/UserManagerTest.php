<?php

namespace Tests\AppBundle\Functional\Services;

use AppBundle\Services\SystemUserService;
use AppBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserManagerTest extends AbstractCoreApplicationServiceTest
{
    const USER_EMAIL = 'user@example.com';
    const USER_PASSWORD = 'password';

    /**
     * @dataProvider getUserDataProvider
     *
     * @param Request $request
     * @param User|null $sessionUser
     * @param User $expectedUser
     */
    public function testGetUser(
        Request $request,
        $sessionUser,
        User $expectedUser
    ) {
        $requestStack = self::$container->get('request_stack');
        $session = self::$container->get('session');
        $userSerializer = self::$container->get(UserSerializer::class);

        $cookieUser = $request->cookies->get(UserManager::USER_COOKIE_KEY);

        if ($cookieUser) {
            $request->cookies->set(UserManager::USER_COOKIE_KEY, $userSerializer->serializeToString($expectedUser));
        }

        $requestStack->push($request);

        if (!empty($sessionUser)) {
            $session->set(UserManager::SESSION_USER_KEY, $userSerializer->serialize($sessionUser));
        }

        $userManager = new UserManager(
            $requestStack,
            $userSerializer,
            $session,
            self::$container->get(SystemUserService::class)
        );

        $user = $userManager->getUser();

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @return array
     */
    public function getUserDataProvider()
    {
        $user = new User(self::USER_EMAIL, self::USER_PASSWORD);

        return [
            'no user in request' => [
                'request' => new Request(),
                'sessionUser' => null,
                'expectedUser' => SystemUserService::getPublicUser(),
            ],
            'invalid user in request, no user in session' => [
                'request' => new Request([], [], [], [
                    UserManager::USER_COOKIE_KEY => false,
                ]),
                'sessionUser' => null,
                'expectedUser' => SystemUserService::getPublicUser(),
            ],
            'valid user in session' => [
                'request' => new Request(),
                'sessionUser' => $user,
                'expectedUser' => $user,
            ],
            'valid user in request, no user in session' => [
                'request' => new Request([], [], [], [
                    UserManager::USER_COOKIE_KEY => true,
                ]),
                'sessionUser' => null,
                'expectedUser' => $user,
            ],
        ];
    }

    public function testSetUser()
    {
        $session = self::$container->get('session');
        $userManager = self::$container->get(UserManager::class);
        $userSerializer = self::$container->get(UserSerializer::class);

        $originalSerializedUser = $session->get(UserManager::SESSION_USER_KEY);

        $user = new User(self::USER_EMAIL, self::USER_PASSWORD);

        $userManager->setUser($user);

        $this->assertEquals($user, $userManager->getUser());

        $currentSerializedUser = $session->get(UserManager::SESSION_USER_KEY);
        $currentUser = $userSerializer->deserialize($currentSerializedUser);

        $this->assertEquals($user, $currentUser);
        $this->assertNotEquals($currentSerializedUser, $originalSerializedUser);
    }

    /**
     * @dataProvider isLoggedInDataProvider
     *
     * @param User|null $user
     * @param bool $expectedIsLoggedIn
     */
    public function testIsLoggedIn(User $user, $expectedIsLoggedIn)
    {
        $userManager = new UserManager(
            self::$container->get('request_stack'),
            self::$container->get(UserSerializer::class),
            self::$container->get('session'),
            self::$container->get(SystemUserService::class)
        );

        $userManager->setUser($user);

        $this->assertEquals($expectedIsLoggedIn, $userManager->isLoggedIn());
    }

    /**
     * @return array
     */
    public function isLoggedInDataProvider()
    {
        return [
            'public user' => [
                'user' => SystemUserService::getPublicUser(),
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

    public function testClearSessionUser()
    {
        $session = self::$container->get('session');
        $userSerializer = self::$container->get(UserSerializer::class);

        $userManager = new UserManager(
            self::$container->get('request_stack'),
            $userSerializer,
            self::$container->get('session'),
            self::$container->get(SystemUserService::class)
        );

        $user = new User(self::USER_EMAIL, self::USER_PASSWORD);
        $serializedUser = $userSerializer->serializeToString($user);

        $session->set(UserManager::SESSION_USER_KEY, $serializedUser);

        $this->assertEquals($serializedUser, $session->get(UserManager::SESSION_USER_KEY));

        $userManager->clearSessionUser();

        $this->assertNull($session->get(UserManager::SESSION_USER_KEY));
    }

    public function testCreateUserCookie()
    {
        $userSerializer = self::$container->get(UserSerializer::class);

        $userManager = new UserManager(
            self::$container->get('request_stack'),
            $userSerializer,
            self::$container->get('session'),
            self::$container->get(SystemUserService::class)
        );

        $user = new User(self::USER_EMAIL, self::USER_PASSWORD);
        $userManager->setUser($user);

        $userCookie = $userManager->createUserCookie();

        $this->assertInstanceOf(Cookie::class, $userCookie);
        $this->assertEquals(UserManager::USER_COOKIE_KEY, $userCookie->getName());

        $cookieSerializedUser = $userCookie->getValue();

        $this->assertEquals($user, $userSerializer->deserializeFromString($cookieSerializedUser));
    }
}
