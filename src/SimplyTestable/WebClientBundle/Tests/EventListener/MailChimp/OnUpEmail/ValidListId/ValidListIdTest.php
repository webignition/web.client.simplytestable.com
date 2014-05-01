<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\OnUpEmail\ValidListId;

use SimplyTestable\WebClientBundle\Tests\EventListener\MailChimp\OnUpEmail\OnUpEmailTest;

abstract class ValidListIdTest extends OnUpEmailTest {
    
    /**
     *
     * @var string
     */
    private $oldEmail = 'old-user@example.com';
    
    
    /**
     *
     * @var string
     */
    private $newEmail = 'new-user@example.com';    
    
    public function setUp() {
        parent::setUp();
        
        $listRecipients = $this->getMailChimpListRecipientsService()->get($this->getListName());
        $listRecipients->addRecipient($this->oldEmail);

        $this->getMailChimpListRecipientsService()->persistAndFlush($listRecipients);
        
        $this->assertTrue($listRecipients->contains($this->oldEmail));
        
        $this->callListener(array(
            'data' => array(
                'list_id' => $this->getMailChimpListRecipientsService()->getListId($this->getListName()),
                'old_email' => $this->oldEmail,
                'new_email' => $this->newEmail
            )
        ));           
    }
    
    
    public function testEmailAddressIsChangedInListRecipients() {
        $this->assertTrue($this->getMailChimpListRecipientsService()->get($this->getListName())->contains($this->newEmail));
        $this->assertFalse($this->getMailChimpListRecipientsService()->get($this->getListName())->contains($this->oldEmail));
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