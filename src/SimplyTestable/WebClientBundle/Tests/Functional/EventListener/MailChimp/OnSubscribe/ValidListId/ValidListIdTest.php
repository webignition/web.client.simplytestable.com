<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnSubscribe\ValidListId;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnSubscribe\OnSubscribeTest;

abstract class ValidListIdTest extends OnSubscribeTest {
    
    /**
     *
     * @var string
     */
    private $email = 'user@example.com';
    
    
    protected function setUp() {
        parent::setUp();
        $this->callListener(array(
            'data' => array(
                'list_id' => $this->getMailChimpListRecipientsService()->getListId($this->getListName()),
                'email' => $this->email              
            )
        ));           
    }
    
    
    public function testEmailAddressIsAddedToListReceipients() {
        $this->assertTrue($this->getMailChimpListRecipientsService()->get($this->getListName())->contains($this->email));
    }
    
    
    /**
     * Derive list name from test namespace
     * 
     * @return string
     */
    private function getListName() {
        $classNameParts = explode('\\', get_class($this));
        return strtolower(str_replace('Test', '', $classNameParts[count($classNameParts) - 1]));
    }
    
}