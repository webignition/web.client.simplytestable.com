<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Entity\MailChimp;

use App\Entity\MailChimp\ListRecipients;
use App\Tests\Services\ObjectReflector;

class ListRecipientsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var ListRecipients
     */
    private $listRecipients;

    protected function setUp()
    {
        parent::setUp();

        $this->listRecipients = new ListRecipients();
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(string $listId, array $recipients)
    {
        $list = ListRecipients::create($listId, $recipients);
        $listRecipients = ObjectReflector::getProperty($list, 'recipients');

        $listIdValue = ObjectReflector::getProperty($list, 'listId');

        $this->assertEquals($listId, $listIdValue);
        $this->assertEquals($recipients, $listRecipients);
    }

    public function createDataProvider(): array
    {
        return [
            'listId only' => [
                'listId' => 'list-id',
                'recipients' => [],
            ],
            'listId and recipients' => [
                'listId' => 'list-id',
                'recipients' => [
                    'user1@example.com',
                    'user2@example.com',
                    'user3@example.com',
                ],
            ],
        ];
    }

    public function testRemoveRecipient()
    {
        $recipient = 'user@example.com';

        $this->listRecipients->addRecipients([$recipient]);
        $this->assertTrue($this->listRecipients->contains($recipient));

        $this->listRecipients->removeRecipient($recipient);
        $this->assertFalse($this->listRecipients->contains($recipient));
    }

    public function testAddRecipients()
    {
        $recipients = [
            'user1@example.com',
            'user2@example.com',
            'user1@example.com',
            'user2@example.com',
            null,
            1,
            true,
        ];

        $expectedRecipients = [
            'user1@example.com',
            'user2@example.com',
        ];

        foreach ($recipients as $recipient) {
            $this->assertFalse($this->listRecipients->contains($recipient));
        }

        $this->listRecipients->addRecipients($recipients);
        $listRecipients = ObjectReflector::getProperty($this->listRecipients, 'recipients');

        $this->assertEquals($expectedRecipients, $listRecipients);
    }
}
