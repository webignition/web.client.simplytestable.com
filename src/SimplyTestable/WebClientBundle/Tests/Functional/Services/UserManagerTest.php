<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Services\UserManager;
use SimplyTestable\WebClientBundle\Services\UserSerializerService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class UserManagerTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @dataProvider getUserDataProvider
     *
     * @param Request $request
     * @param UserSerializerService|MockInterface $userSerializerService
     * @param User $expectedUser
     */
    public function testGetUser(Request $request, UserSerializerService $userSerializerService, User $expectedUser)
    {
        $userSerializerService
            ->shouldReceive('serialize')
            ->withArgs(function (User $user) use ($expectedUser) {
                return $expectedUser->equals($user);
            });

        $userManager = $this->createUserManager($request, $userSerializerService);

        $user = $userManager->getUser();

        $this->assertEquals($expectedUser, $user);
    }

    /**
     * @return array
     */
    public function getUserDataProvider()
    {
        $user = new User('user@example.com');
        $serializedUser = 'foo';

        return [
            'no user in request' => [
                'request' => new Request(),
                'userSerializerService' => $this->createUserSerializerService('', null),
                'expectedUser' => SystemUserService::getPublicUser(),
            ],
            'invalid user in request' => [
                'request' => new Request([], [], [], [
                    UserManager::USER_COOKIE_KEY => $serializedUser,
                ]),
                'userSerializerService' => $this->createUserSerializerService($serializedUser, null),
                'expectedUser' => SystemUserService::getPublicUser(),
            ],
            'valid user in request' => [
                'request' => new Request([], [], [], [
                    UserManager::USER_COOKIE_KEY => $serializedUser,
                ]),
                'userSerializerService' => $this->createUserSerializerService($serializedUser, $user),
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

    /**
     * @param Request $request
     * @param UserSerializerService $userSerializerService
     *
     * @return UserManager
     */
    private function createUserManager(Request $request, UserSerializerService $userSerializerService)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return new UserManager(
            $requestStack,
            $userSerializerService,
            $this->container->get('session')
        );
    }

    /**
     * @param string $serializedUser
     * @param User|null $user
     *
     * @return MockInterface|UserSerializerService
     */
    private function createUserSerializerService($serializedUser, $user = null)
    {
        $userSerializerService = \Mockery::mock(UserSerializerService::class);

        $userSerializerService
            ->shouldReceive('unserializedFromString')
            ->with($serializedUser)
            ->andReturn($user);

        return $userSerializerService;
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
