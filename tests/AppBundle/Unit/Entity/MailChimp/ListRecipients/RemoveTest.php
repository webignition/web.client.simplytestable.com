<?php

namespace Tests\AppBundle\Unit\Entity\MailChimp\ListRecipients;

class RemoveTest extends EntityTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->listRecipients->addRecipient('foo');
    }

    public function testRemove()
    {
        $this->assertFalse($this->listRecipients->removeRecipient('foo')->contains('foo'));
    }
}
