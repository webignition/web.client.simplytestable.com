<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\EmailList;

use App\Command\EmailList\UnsubscribeCommand;
use Symfony\Component\Console\Output\NullOutput;

class UnsubscribeCommandTest extends AbstractSubscriptionCommandTest
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

        $mailChimpService = $this->createMailChimpServiceWithIsCalledExpectation('unsubscribe', [
            $listId,
            $email,
        ]);

        $this->setMailChimpServiceOnCommand($this->unsubscribeCommand, $mailChimpService);

        $returnValue = $this->unsubscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }

    /**
     * @dataProvider runIsNotCalledDataProvider
     */
    public function testRunUnsubscribeIsNotCalled(?string $listId, ?string $email)
    {
        $mailChimpService = $this->createMailChimpServiceWithIsNotCalledExpectation('unsubscribe');
        $this->setMailChimpServiceOnCommand($this->unsubscribeCommand, $mailChimpService);

        $returnValue = $this->unsubscribeCommand->run($this->createInput($listId, $email), new NullOutput());

        $this->assertEquals(0, $returnValue);
    }
}
