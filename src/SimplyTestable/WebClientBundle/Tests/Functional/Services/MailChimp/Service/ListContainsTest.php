<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\Service;

class ListContainsTest extends ServiceTest {   

    
    public function testListDoesContain() {        
        $email = 'user@example.com';
        $listName = 'updates';
        
        $listRecipients = $this->getMailChimpListRecipientsService()->get($listName);        
        $listRecipients->addRecipient($email);
        $this->getMailChimpListRecipientsService()->persistAndFlush($listRecipients);

        $this->assertTrue($this->getMailchimpService()->listContains($listName, $email));
    }
    

    public function testListDoesNotContain() {        
        $this->assertFalse($this->getMailchimpService()->listContains('updates', 'foo@bar.com'));
    }      

}
