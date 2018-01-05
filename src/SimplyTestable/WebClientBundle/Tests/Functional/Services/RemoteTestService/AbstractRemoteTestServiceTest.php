<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\RemoteTestService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

abstract class AbstractRemoteTestServiceTest extends BaseSimplyTestableTestCase
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

        $this->remoteTestService = $this->container->get(
            'simplytestable.services.remotetestservice'
        );

        $this->user = new User('user@example.com');
    }

    /**
     * @param Test $test
     */
    protected function setRemoteTestServiceTest(Test $test)
    {
        $reflectionClass = new ReflectionClass(RemoteTestService::class);

        $reflectionProperty = $reflectionClass->getProperty('test');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->remoteTestService, $test);
    }
}
