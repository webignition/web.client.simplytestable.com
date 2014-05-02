<?php

namespace SimplyTestable\WebClientBundle\Tests\Entity\MailChimp\ListRecipients;

class AddTest extends EntityTest {
    
    public function testAdd() {
        $this->assertTrue($this->listRecipients->addRecipient('foo')->contains('foo'));
    } 

}
