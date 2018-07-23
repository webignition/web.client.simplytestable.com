<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use ReflectionClass;
use App\Entity\Test\Test;
use App\Model\RemoteTest\RemoteTest;
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

    /**
     * @param Test $test
     *
     * @throws \ReflectionException
     */
    protected function setRemoteTestServiceTest(Test $test)
    {
        $reflectionClass = new ReflectionClass(RemoteTestService::class);

        $reflectionProperty = $reflectionClass->getProperty('test');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->remoteTestService, $test);
    }

    /**
     * @return RemoteTest $test
     *
     * @throws \ReflectionException
     */
    protected function getRemoteTestServiceRemoteTest()
    {
        $reflectionClass = new ReflectionClass(RemoteTestService::class);

        $reflectionProperty = $reflectionClass->getProperty('remoteTest');
        $reflectionProperty->setAccessible(true);

        return $reflectionProperty->getValue($this->remoteTestService);
    }
}
