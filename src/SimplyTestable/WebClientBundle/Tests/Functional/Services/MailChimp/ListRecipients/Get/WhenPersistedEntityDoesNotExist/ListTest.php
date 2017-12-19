<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get\WhenPersistedEntityDoesNotExist;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get\KnownListTest;

abstract class ListTest extends KnownListTest {
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients 
     */
    private $listRecipients;
    
    
    public function setUp() {
        parent::setUp();
        $this->listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
    }   
    
    public function testListId() {
        $this->assertEquals($this->getMailChimpListRecipientsService()->getListId($this->getListName()), $this->listRecipients->getListId());
    } 

}
