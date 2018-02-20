<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\CoreApplicationHttpClient;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use Tests\WebClientBundle\Functional\Services\AbstractCoreApplicationServiceTest;

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

        $this->remoteTestService = $this->container->get(RemoteTestService::class);

        $this->user = new User('user@example.com');

        $coreApplicationHttpClient = $this->container->get(CoreApplicationHttpClient::class);
        $coreApplicationHttpClient->setUser($this->user);
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
