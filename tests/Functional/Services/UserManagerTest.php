<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\SystemUserService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use webignition\SimplyTestableUserHydrator\UserHydrator;
use webignition\SimplyTestableUserModel\User;
use webignition\SimplyTestableUserSerializer\UserSerializer;

class UserManagerTest extends AbstractCoreApplicationServiceTest
{
    const USER_EMAIL = 'user@example.com';
    const USER_PASSWORD = 'password';

    /**
     * @dataProvider getUserDataProvider
     */
    public function testGetUser(Request $request, ?User $sessionUser, User $expectedUser)
    {
        $requestStack = self::$container->get(RequestStack::class);
        $session = self::$container->get(SessionInterface::class);
        $userSerializer = self::$container->get(UserSerializer::class);

        $cookieUser = $request->cookies->get(UserManager::USER_COOKIE_KEY);

        if ($cookieUser) {
            $request->cookies->set(UserManager::USER_COOKIE_KEY, $userSerializer->serializeToString($expectedUser));
        }

        $requestStack->push($request);

        if (!empty($sessionUser)) {
            $session->set(UserManager::SESSION_USER_KEY, $userSerializer->serialize($sessionUser));
        }

        $userHydrator = new UserHydrator($requestStack, $userSerializer, $session);

        $userManager = new UserManager(
            $userSerializer,
            $session,
            self::$container->get(SystemUserService::class),
            $userHydrator
        );

        $user = $userManager->getUser();

        $this->assertEquals($expectedUser, $user);
    }

    public function getUserDataProvider(): array
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
        $session = self::$container->get(SessionInterface::class);
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
     */
    public function testIsLoggedIn(User $user, bool $expectedIsLoggedIn)
    {
        $userManager = self::$container->get(UserManager::class);

        $userManager->setUser($user);

        $this->assertEquals($expectedIsLoggedIn, $userManager->isLoggedIn());
    }

    public function isLoggedInDataProvider(): array
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
        $session = self::$container->get(SessionInterface::class);
        $userSerializer = self::$container->get(UserSerializer::class);

        $userManager = self::$container->get(UserManager::class);

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

        $userManager = self::$container->get(UserManager::class);

        $user = new User(self::USER_EMAIL, self::USER_PASSWORD);
        $userManager->setUser($user);

        $userCookie = $userManager->createUserCookie();

        $this->assertInstanceOf(Cookie::class, $userCookie);
        $this->assertEquals(UserManager::USER_COOKIE_KEY, $userCookie->getName());

        $cookieSerializedUser = $userCookie->getValue();

        $this->assertEquals($user, $userSerializer->deserializeFromString($cookieSerializedUser));
    }
}
