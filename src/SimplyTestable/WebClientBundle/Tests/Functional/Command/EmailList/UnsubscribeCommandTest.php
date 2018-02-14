<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command\EmailList;

use GuzzleHttp\Subscriber\Mock as MockSubscriber;
use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;
use SimplyTestable\WebClientBundle\Command\EmailList\UnsubscribeCommand;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class UnsubscribeCommandTest extends AbstractBaseTestCase
{
    /**
     * @var UnsubscribeCommand
     */
    protected $unsubscribeCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->unsubscribeCommand = $this->container->get(UnsubscribeCommand::class);
    }

    public function testRun()
    {
        $mailChimpClient = $this->container->get(Client::class);

        $mockSubscriber = new MockSubscriber([
            HttpResponseFactory::createSuccessResponse(),
        ]);
        $mailChimpClient->getHttpClient()->getEmitter()->attach($mockSubscriber);

        $input = new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => 'announcements',
            SubscribeCommand::ARG_EMAIL => 'user@example.com',
        ]);

        $returnValue = $this->unsubscribeCommand->run($input, new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
