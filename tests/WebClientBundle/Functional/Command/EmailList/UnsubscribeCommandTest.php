<?php

namespace Tests\WebClientBundle\Functional\Command\EmailList;

use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;
use SimplyTestable\WebClientBundle\Command\EmailList\UnsubscribeCommand;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Tests\WebClientBundle\Services\HttpMockHandler;

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

        $this->unsubscribeCommand = self::$container->get(UnsubscribeCommand::class);
    }

    public function testRun()
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);

        $input = new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => 'announcements',
            SubscribeCommand::ARG_EMAIL => 'user@example.com',
        ]);

        $returnValue = $this->unsubscribeCommand->run($input, new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
