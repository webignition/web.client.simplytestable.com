<?php

namespace Tests\AppBundle\Functional\Services\RemoteTestService;

use ReflectionClass;
use AppBundle\Entity\Test\Test;
use AppBundle\Model\RemoteTest\RemoteTest;
use AppBundle\Services\RemoteTestService;
use Tests\AppBundle\Functional\Services\AbstractCoreApplicationServiceTest;
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
