<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command\EmailList;

use GuzzleHttp\Subscriber\Mock as MockSubscriber;
use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;
use SimplyTestable\WebClientBundle\Services\MailChimp\Client;
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
        $mailChimpClient = $this->container->get(Client::class);

        $mockSubscriber = new MockSubscriber([
            HttpResponseFactory::createSuccessResponse(),
        ]);
        $mailChimpClient->getHttpClient()->getEmitter()->attach($mockSubscriber);

        $input = new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => 'announcements',
            SubscribeCommand::ARG_EMAIL => 'user@example.com',
        ]);

        $returnValue = $this->subscribeCommand->run($input, new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
