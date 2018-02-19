<?php

namespace Tests\ApiBundle\Functional\Resque\Job;

use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;
use SimplyTestable\WebClientBundle\Resque\Job\EmailListSubscribeJob;
use Tests\WebClientBundle\Functional\Resque\Job\AbstractJobTest;

class EmailListSubscribeJobTest extends AbstractJobTest
{
    const USER_EMAIL = 'user@example.com';

    /**
     * @dataProvider runDataProvider
     *
     * @param int $commandReturnCode
     * @param bool|int $expectedJobReturnValue
     */
    public function testRun($commandReturnCode, $expectedJobReturnValue)
    {
        $announcementsListId = $this->container->getParameter('mailchimp_announcements_list_id');

        /* @var SubscribeCommand|MockInterface $command */
        $command = \Mockery::mock($this->container->get(SubscribeCommand::class));
        $command
            ->shouldReceive('run')
            ->andReturn($commandReturnCode);

        $job = $this->createJob([
            'listId' => $announcementsListId,
            'email' => self::USER_EMAIL,
        ], 'email-list-subscribe', $command);

        $this->assertInstanceOf(EmailListSubscribeJob::class, $job);

        $returnValue = $job->run([]);

        $this->assertEquals($expectedJobReturnValue, $returnValue);
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            'command returns zero' => [
                'commandReturnCode' => 0,
                'expectedJobReturnCode' => true,
            ],
            'command returns 2' => [
                'commandReturnCode' => 2,
                'expectedJobReturnCode' => 2,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
