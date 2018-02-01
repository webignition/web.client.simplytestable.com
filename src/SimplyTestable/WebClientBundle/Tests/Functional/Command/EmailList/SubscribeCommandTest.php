<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command\EmailList;

use Guzzle\Plugin\Mock\MockPlugin;
use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class SubscribeCommandTest extends AbstractBaseTestCase
{
    /**
     * @var SubscribeCommand
     */
    protected $subscribeCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->subscribeCommand = $this->container->get(SubscribeCommand::class);
    }

    public function testRun()
    {
        $mailChimpClient = $this->container->get('simplytestable.services.mailchimp.client');
        $mailChimpClient->addSubscriber(new MockPlugin([
            HttpResponseFactory::createSuccessResponse(),
        ]));

        $input = new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => 'announcements',
            SubscribeCommand::ARG_EMAIL => 'user@example.com',
        ]);

        $returnValue = $this->subscribeCommand->run($input, new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
