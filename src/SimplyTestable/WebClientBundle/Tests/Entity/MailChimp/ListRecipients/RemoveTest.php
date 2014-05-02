<?php

namespace SimplyTestable\WebClientBundle\Tests\Entity\MailChimp\ListRecipients;

class RemoveTest extends EntityTest {
    
    public function setUp() {
        parent::setUp();
        $this->listRecipients->addRecipient('foo');
    }
    
    public function testRemove() {
        $this->assertFalse($this->listRecipients->removeRecipient('foo')->contains('foo'));
    } 

}
