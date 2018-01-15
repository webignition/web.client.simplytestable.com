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
}
