<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\SubscribeCommand;
use Symfony\Component\Console\Output\NullOutput;

class SubscribeCommandTest extends AbstractSubscriptionCommandTest
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

        $mailChimpService = $this->createMailChimpServiceWithIsCalledExpectation('subscribe', [
            $listId,
            $email,
        ]);

        $this->setMailChimpServiceOnCommand($this->subscribeCommand, $mailChimpService);

        $returnValue = $this->subscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    /**
     * @dataProvider runIsNotCalledDataProvider
     */
    public function testRunSubscribeIsNotCalled(?string $listId, ?string $email)
    {
        $mailChimpService = $this->createMailChimpServiceWithIsNotCalledExpectation('subscribe');
        $this->setMailChimpServiceOnCommand($this->subscribeCommand, $mailChimpService);

        $returnValue = $this->subscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
