<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\AbstractEmailListCommand;
use App\Command\EmailList\SubscribeCommand;
use App\Services\MailChimp\Service;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\ObjectReflector;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
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

        $this->subscribeCommand = self::$container->get(SubscribeCommand::class);
    }

    public function testRunSubscribeIsCalled()
    {
        $listId = 'announcements';
        $email = 'user@example.com';

        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldReceive('subscribe')
            ->once()
            ->with($listId, $email)
            ->andReturn(true);

        $this->setMailChimpServiceOnSubscribeCommand($mailChimpService);

        $returnValue = $this->subscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    /**
     * @dataProvider runSubscribeIsNotCalledDataProvider
     */
    public function testRunSubscribeIsNotCalled(?string $listId, ?string $email)
    {
        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldNotReceive('subscribe')
            ->with($listId, $email);

        $this->setMailChimpServiceOnSubscribeCommand($mailChimpService);

        $returnValue = $this->subscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    public function runSubscribeIsNotCalledDataProvider(): array
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

    private function createInput(?string $listId, ?string $email): InputInterface
    {
        return new ArrayInput([
            SubscribeCommand::ARG_LIST_ID => $listId,
            SubscribeCommand::ARG_EMAIL => $email,
        ]);
    }

    private function setMailChimpServiceOnSubscribeCommand(Service $mailChimpService)
    {
        ObjectReflector::setProperty(
            $this->subscribeCommand,
            AbstractEmailListCommand::class,
            'mailChimpService',
            $mailChimpService
        );
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
