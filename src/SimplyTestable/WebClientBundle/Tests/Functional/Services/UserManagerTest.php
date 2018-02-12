<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use Symfony\Component\HttpFoundation\Request;

class UserManagerTest extends AbstractCoreApplicationServiceTest
{
    const USER_EMAIL = 'user@example.com';

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
        $requestStack = $this->container->get('request_stack');
        $session = $this->container->get('session');
        $userSerializer = $this->container->get('simplytestable.services.userserializerservice');

        $privateUser = new User(self::USER_EMAIL);

        $cookieUser = $request->cookies->get(UserManager::USER_COOKIE_KEY);

        if ($cookieUser) {
            $request->cookies->set(UserManager::USER_COOKIE_KEY, $userSerializer->serializeToString($privateUser));
        }

        $requestStack->push($request);

        if (!empty($sessionUser)) {
            $session->set(UserManager::SESSION_USER_KEY, $userSerializer->serialize($sessionUser));
        }

        $userManager = new UserManager(
            $requestStack,
            $userSerializer,
            $session
        );

        $user = $userManager->getUser();

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @return array
     */
    public function getUserDataProvider()
    {
        $user = new User(self::USER_EMAIL);

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
        $session = $this->container->get('session');
        $userManager = $this->container->get(UserManager::class);
        $userSerializer = $this->container->get('simplytestable.services.userserializerservice');

        $originalSerializedUser = $session->get(UserManager::SESSION_USER_KEY);

        $user = new User('user@example.com');

        $userManager->setUser($user);

        $this->assertEquals($user, $userManager->getUser());

        $currentSerializedUser = $session->get(UserManager::SESSION_USER_KEY);
        $currentUser = $userSerializer->unserialize($currentSerializedUser);

        $this->assertEquals($user, $currentUser);
        $this->assertNotEquals($currentSerializedUser, $originalSerializedUser);
    }
}
