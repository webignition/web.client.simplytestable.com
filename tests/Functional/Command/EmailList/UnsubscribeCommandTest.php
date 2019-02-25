<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\AbstractEmailListCommand;
use App\Command\EmailList\SubscribeCommand;
use App\Command\EmailList\UnsubscribeCommand;
use App\Services\MailChimp\Service;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\ObjectReflector;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
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

        $this->unsubscribeCommand = self::$container->get(UnsubscribeCommand::class);
    }

    public function testRunUnsubscribeIsCalled()
    {
        $listId = 'announcements';
        $email = 'user@example.com';

        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldReceive('unsubscribe')
            ->once()
            ->with($listId, $email)
            ->andReturn(true);

        $this->setMailChimpServiceOnSubscribeCommand($mailChimpService);

        $returnValue = $this->unsubscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    /**
     * @dataProvider runUnsubscribeIsNotCalledDataProvider
     */
    public function testRunUnsubscribeIsNotCalled(?string $listId, ?string $email)
    {
        $mailChimpService = \Mockery::mock(Service::class);
        $mailChimpService
            ->shouldNotReceive('unsubscribe')
            ->with($listId, $email);

        $this->setMailChimpServiceOnSubscribeCommand($mailChimpService);

        $returnValue = $this->unsubscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    public function runUnsubscribeIsNotCalledDataProvider(): array
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
            $this->unsubscribeCommand,
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
