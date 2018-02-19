<?php

namespace Tests\WebClientBundle\Functional\Services\Resque;

use SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob;
use SimplyTestable\WebClientBundle\Resque\Job\EmailListUnsubscribeJob;
use webignition\ResqueJobFactory\ResqueJobFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class JobFactoryTest extends AbstractBaseTestCase
{
    /**
     * @var ResqueJobFactory
     */
    private $jobFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->jobFactory = $this->container->get(ResqueJobFactory::class);
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param string $queue
     * @param array $args
     * @param string $expectedJobClass
     * @param string $expectedQueue
     * @param array $expectedArgs
     */
    public function testCreate($queue, $args, $expectedJobClass, $expectedQueue, $expectedArgs)
    {
        $job = $this->jobFactory->create($queue, $args);

        $this->assertInstanceOf($expectedJobClass, $job);
        $this->assertEquals($job->queue, $expectedQueue);
        $this->assertEquals($job->args, $expectedArgs);
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            'email-list-subscribe' => [
                'queue' => 'email-list-subscribe',
                'args' => [
                    'listId' => 'announcements',
                    'email' => 'user@example.com',
                ],
                'expectedJobClass' => EmailListSubscribeJob::class,
                'expectedQueue' => 'email-list-subscribe',
                'expectedArgs' => [
                    'listId' => 'announcements',
                    'email' => 'user@example.com',
                ],
            ],
            'email-list-unsubscribe' => [
                'queue' => 'email-list-unsubscribe',
                'args' => [
                    'listId' => 'announcements',
                    'email' => 'user@example.com',
                ],
                'expectedJobClass' => EmailListUnsubscribeJob::class,
                'expectedQueue' => 'email-list-unsubscribe',
                'expectedArgs' => [
                    'listId' => 'announcements',
                    'email' => 'user@example.com',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getJobClassNameDataProvider
     *
     * @param string $queue
     * @param string $expectedJobClassName
     */
    public function testGetJobClassName($queue, $expectedJobClassName)
    {
        $jobClassName = $this->jobFactory->getJobClassName($queue);

        $this->assertEquals($expectedJobClassName, $jobClassName);
    }

    /**
     * @return array
     */
    public function getJobClassNameDataProvider()
    {
        return [
            'email-list-subscribe' => [
                'queue' => 'email-list-subscribe',
                'expectedJobClassName' => EmailListSubscribeJob::class,
            ],
            'email-list-unsubscribe' => [
                'queue' => 'email-list-unsubscribe',
                'expectedJobClassName' => EmailListUnsubscribeJob::class,
            ],
            'foo' => [
                'queue' => 'foo',
                'expectedJobClassName' => null,
            ],
        ];
    }
}
