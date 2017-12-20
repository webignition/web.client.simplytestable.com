<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients;

class HasListIdentifierTest extends ServiceTest {
    
    public function testHasAnnouncements() {
        $this->assertTrue($this->getMailChimpListRecipientsService()->hasListIdentifier('announcements'));
    }    
    
    public function testHasUpdates() {
        $this->assertTrue($this->getMailChimpListRecipientsService()->hasListIdentifier('updates'));
    }    
    
    public function testHasIntroduction() {
        $this->assertTrue($this->getMailChimpListRecipientsService()->hasListIdentifier('introduction'));
    }        
    
    public function testHasNotFoo() {
        $this->assertFalse($this->getMailChimpListRecipientsService()->hasListIdentifier('foo'));
    }        

}
