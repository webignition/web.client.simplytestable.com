<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Entity\MailChimp\ListRecipients;

class CountTest extends EntityTest
{
    public function testAddOneRecipientReturnsCountOf1()
    {
        $this->listRecipients->addRecipient('foo');
        $this->assertEquals(1, $this->listRecipients->count());
    }

    public function testAddTwoRecipientsReturnsCountOf2()
    {
        $this->listRecipients->addRecipient('foo');
        $this->listRecipients->addRecipient('bar');
        $this->assertEquals(2, $this->listRecipients->count());
    }

    public function testAddThreeRecipientsReturnsCountOf3()
    {
        $this->listRecipients->addRecipient('foo');
        $this->listRecipients->addRecipient('bar');
        $this->listRecipients->addRecipient('foobar');
        $this->assertEquals(3, $this->listRecipients->count());
    }
}
