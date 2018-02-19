<?php

namespace Tests\WebClientBundle\Unit\Entity\MailChimp\ListRecipients;

class ContainsTest extends EntityTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->listRecipients->setRecipients(array(
            'foo'
        ));
    }

    public function testContains()
    {
        $this->assertTrue($this->listRecipients->contains('foo'));
    }


    public function testNotContains()
    {
        $this->assertFalse($this->listRecipients->contains('bar'));
    }
}
