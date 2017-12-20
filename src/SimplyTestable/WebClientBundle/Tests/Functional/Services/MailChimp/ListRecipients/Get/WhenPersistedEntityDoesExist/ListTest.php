<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get\WhenPersistedEntityDoesExist;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get\KnownListTest;

abstract class ListTest extends KnownListTest {
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients 
     */
    private $listRecipients;
    
    
    /**
     *
     * @var string[]
     */
    private $recipients = array(
        'foo',
        'bar',
        'foobar'
    );
    
    
    protected function setUp() {
        parent::setUp();
        $this->listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
        
        foreach ($this->recipients as $recipient) {
            $this->listRecipients->addRecipient($recipient);
        }
        
        $this->getMailChimpListRecipientsService()->persistAndFlush($this->listRecipients);
        
        $this->getMailChimpListRecipientsService()->getEntityRepository()->clear();
        
        $this->listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
    }
    
    
    public function testListId() {
        $this->assertEquals($this->getMailChimpListRecipientsService()->getListId($this->getListName()), $this->listRecipients->getListId());
    } 

}
