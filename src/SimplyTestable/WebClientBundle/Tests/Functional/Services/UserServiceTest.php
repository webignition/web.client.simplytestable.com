<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\UserService;

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
}
