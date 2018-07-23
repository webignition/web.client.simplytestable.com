<?php

namespace App\Tests\Functional\Services;

use App\Services\SystemUserService;
use App\Tests\Functional\AbstractBaseTestCase;
use webignition\SimplyTestableUserModel\User;

class SystemUserServiceTest extends AbstractBaseTestCase
{
    /**
     * @var SystemUserService
     */
    private $systemUserService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->systemUserService = self::$container->get(SystemUserService::class);
    }

    public function testGetPublicUser()
    {
        $expectedPublicUser = new User(
            SystemUserService::PUBLIC_USER_USERNAME,
            SystemUserService::PUBLIC_USER_PASSWORD
        );

        $this->assertEquals($expectedPublicUser, SystemUserService::getPublicUser());
    }

    public function testGetAdminUser()
    {
        $expectedAdminUser = new User(
            self::$container->getParameter('admin_user_username'),
            self::$container->getParameter('admin_user_password')
        );

        $this->assertEquals($expectedAdminUser, $this->systemUserService->getAdminUser());
    }

    /**
     * @dataProvider isPublicUserDataProvider
     *
     * @param User $user
     * @param bool $expectedIsPublicUser
     */
    public function testIsPublicUser(User $user, $expectedIsPublicUser)
    {
        $this->assertEquals($expectedIsPublicUser, SystemUserService::isPublicUser($user));
    }

    /**
     * @return array
     */
    public function isPublicUserDataProvider()
    {
        return [
            'public user username' => [
                'user' => new User(SystemUserService::PUBLIC_USER_USERNAME),
                'expectedIsPublicUser' => true,
            ],
            'public user email' => [
                'user' => new User(SystemUserService::PUBLIC_USER_EMAIL),
                'expectedIsPublicUser' => true,
            ],
            'not public user' => [
                'user' => new User('user@example.com'),
                'expectedIsPublicUser' => false,
            ],
        ];
    }
}
