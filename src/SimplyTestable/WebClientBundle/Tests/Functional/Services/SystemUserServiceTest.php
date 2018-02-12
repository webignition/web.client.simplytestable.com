<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\SystemUserService;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

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

        $this->systemUserService = $this->container->get(SystemUserService::class);
    }

    public function testGetPublicUser()
    {
        $expectedPublicUser = new User(
            SystemUserService::PUBLIC_USER_USERNAME,
            SystemUserService::PUBLIC_USER_PASSWORD
        );

        $this->assertEquals($expectedPublicUser, $this->systemUserService->getPublicUser());
    }

    public function testGetAdminUser()
    {
        $expectedAdminUser = new User(
            $this->container->getParameter('admin_user_username'),
            $this->container->getParameter('admin_user_password')
        );

        $this->assertEquals($expectedAdminUser, $this->systemUserService->getAdminUser());
    }
}
