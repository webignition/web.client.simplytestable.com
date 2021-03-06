<?php
/** @noinspection PhpDocSignatureInspection */

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
            getenv('ADMIN_USER_USERNAME'),
            getenv('ADMIN_USER_PASSWORD')
        );

        $this->assertEquals($expectedAdminUser, $this->systemUserService->getAdminUser());
    }

    /**
     * @dataProvider isPublicUserDataProvider
     */
    public function testIsPublicUser(User $user, bool $expectedIsPublicUser)
    {
        $this->assertEquals($expectedIsPublicUser, SystemUserService::isPublicUser($user));
    }

    public function isPublicUserDataProvider(): array
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
