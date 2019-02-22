<?php

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

    public function testAddRecipient()
    {
        $recipient = 'user@example.com';

        $this->assertFalse($this->listRecipients->contains($recipient));

        $this->listRecipients->addRecipient($recipient);

        $this->assertTrue($this->listRecipients->contains($recipient));
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->listRecipients->count());

        $this->listRecipients->addRecipient('user1@example.com');
        $this->assertEquals(1, $this->listRecipients->count());

        $this->listRecipients->addRecipient('user2@example.com');
        $this->assertEquals(2, $this->listRecipients->count());
    }

    public function testGetId()
    {
        $this->assertNull($this->listRecipients->getId());
    }

    public function testSetGetListId()
    {
        $listId = 'foo';

        $this->assertNull($this->listRecipients->getListId());

        $this->listRecipients->setListId($listId);

        $this->assertEquals($listId, $this->listRecipients->getListId());
    }

    public function testRemoveRecipient()
    {
        $recipient = 'user@example.com';

        $this->listRecipients->addRecipient($recipient);
        $this->assertTrue($this->listRecipients->contains($recipient));

        $this->listRecipients->removeRecipient($recipient);
        $this->assertFalse($this->listRecipients->contains($recipient));
    }

    public function testSetRecipients()
    {
        $recipients = [
            'user1@example.com',
            'user2@example.com',
            'user2@example.com',
        ];

        foreach ($recipients as $recipient) {
            $this->assertFalse($this->listRecipients->contains($recipient));
        }

        $this->listRecipients->setRecipients($recipients);

        foreach ($recipients as $recipient) {
            $this->assertTrue($this->listRecipients->contains($recipient));
        }
    }
}
