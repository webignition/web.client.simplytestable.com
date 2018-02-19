<?php

namespace Tests\ApiBundle\Functional\Resque\Job;

use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Command\EmailList\UnsubscribeCommand;
use SimplyTestable\WebClientBundle\Resque\Job\EmailListUnsubscribeJob;
use SimplyTestable\WebClientBundle\Tests\Functional\Resque\Job\AbstractJobTest;

class EmailListUnsubscribeJobTest extends AbstractJobTest
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

        /* @var UnsubscribeCommand|MockInterface $command */
        $command = \Mockery::mock($this->container->get(UnsubscribeCommand::class));
        $command
            ->shouldReceive('run')
            ->andReturn($commandReturnCode);

        $job = $this->createJob([
            'listId' => $announcementsListId,
            'email' => self::USER_EMAIL,
        ], 'email-list-unsubscribe', $command);

        $this->assertInstanceOf(EmailListUnsubscribeJob::class, $job);

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
