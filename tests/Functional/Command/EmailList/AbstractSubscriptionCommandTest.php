<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\AbstractEmailListCommand;
use App\Command\EmailList\SubscribeCommand;
use App\Services\MailChimp\Service;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\ObjectReflector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractSubscriptionCommandTest extends AbstractBaseTestCase
{
    public function runIsNotCalledDataProvider(): array
    {
        return [
            'null listId, null email' => [
                'listId' => null,
                'email' => null,
            ],
            'empty listId, empty email' => [
                'listId' => '',
                'email' => '',
            ],
        ];
    }

    protected function createInput(?string $listId, ?string $email): InputInterface
    {
        return new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => $listId,
            SubscribeCommand::ARG_EMAIL => $email,
        ]);
    }

    protected function setMailChimpServiceOnCommand(Command $command, Service $mailChimpService)
    {
        ObjectReflector::setProperty(
            $command,
            AbstractEmailListCommand::class,
            'mailChimpService',
            $mailChimpService
        );
    }

    protected function createMailChimpServiceWithIsCalledExpectation(string $method, array $args)
    {
        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldReceive($method)
            ->once()
            ->withArgs($args);

        return $mailChimpService;
    }

    protected function createMailChimpServiceWithIsNotCalledExpectation(string $method)
    {
        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldNotReceive($method);

        return $mailChimpService;
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
