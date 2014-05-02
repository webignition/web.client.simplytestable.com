<?php

namespace SimplyTestable\WebClientBundle\Tests\Entity\MailChimp\ListRecipients;

class UniqueTest extends EntityTest {
    
    public function setUp() {
        parent::setUp();
        
        $this->listRecipients->addRecipient('foo');
        $this->listRecipients->addRecipient('bar');
        $this->listRecipients->addRecipient('foobar');
        $this->listRecipients->addRecipient('foo');
        $this->listRecipients->addRecipient('bar');
        $this->listRecipients->addRecipient('foobar');        
    }
    
    public function testCountReturns3() {
        $this->assertEquals(3, $this->listRecipients->count());
    }

}
