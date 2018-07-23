<?php

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\SubscribeCommand;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use App\Tests\Services\HttpMockHandler;

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

        $this->subscribeCommand = self::$container->get(SubscribeCommand::class);
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

        $returnValue = $this->subscribeCommand->run($input, new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
