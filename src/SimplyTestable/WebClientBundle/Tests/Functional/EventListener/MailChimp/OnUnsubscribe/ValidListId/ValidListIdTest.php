<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnUnsubscribe\ValidListId;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnUnsubscribe\OnUnsubscribeTest;

abstract class ValidListIdTest extends OnUnsubscribeTest {
    
    /**
     *
     * @var string
     */
    private $email = 'user@example.com';
    
    protected function setUp() {
        parent::setUp();
        
        $listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
        $listRecipients->addRecipient($this->email);

        $this->getMailChimpListRecipientsService()->persistAndFlush($listRecipients);
        
        $this->assertTrue($listRecipients->contains($this->email));
        
        $this->callListener(array(
            'data' => array(
                'list_id' => $this->getMailChimpListRecipientsService()->getListId($this->getListName()),
                'email' => $this->email              
            )
        ));           
    }
    
    
    public function testEmailAddressIsRemovedFromListReceipients() {
        $this->assertFalse($this->getMailChimpListRecipientsService()->get($this->getListName())->contains($this->email));
    }
    
    
    /**
     * Derive list name from test namespace
     * 
     * @return string
     */
    protected function getListName() {
        $classNameParts = explode('\\', get_class($this));
        return strtolower(str_replace('Test', '', $classNameParts[count($classNameParts) - 1]));
    }
    
}