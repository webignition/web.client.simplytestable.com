<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Entity\MailChimp;

use App\Entity\MailChimp\ListRecipients;

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
        $listRecipients = ListRecipients::create($listId, $recipients);

        $this->assertEquals($listId, $listRecipients->getListId());
        $this->assertEquals($recipients, $listRecipients->getRecipients());
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

    public function testCount()
    {
        $this->assertEquals(0, $this->listRecipients->count());

        $this->listRecipients->addRecipients(['user1@example.com']);
        $this->assertEquals(1, $this->listRecipients->count());

        $this->listRecipients->addRecipients(['user2@example.com']);
        $this->assertEquals(2, $this->listRecipients->count());
    }

    public function testSetGetListId()
    {
        $listId = 'foo';

        $this->assertEquals('', $this->listRecipients->getListId());

        $this->listRecipients->setListId($listId);

        $this->assertEquals($listId, $this->listRecipients->getListId());
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
        $this->assertEquals($expectedRecipients, $this->listRecipients->getRecipients());
    }
}
