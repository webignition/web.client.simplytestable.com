<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Services\RemoteTestService;
use App\Tests\Functional\Services\AbstractCoreApplicationServiceTest;
use webignition\SimplyTestableUserModel\User;

abstract class AbstractRemoteTestServiceTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var RemoteTestService
     */
    protected $remoteTestService;

    /**
     * @var User
     */
    protected $user;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->remoteTestService = self::$container->get(RemoteTestService::class);

        $this->user = new User('user@example.com');
    }
}
