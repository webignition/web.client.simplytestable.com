<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnSubscribe\ValidListId;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnSubscribe\OnSubscribeTest;

class InvalidListIdTest extends OnSubscribeTest {    
    
    const LIST_ID  = 'foo';
    
    public function testDomainExceptionIsThrown() {
        $this->setExpectedException('\DomainException', 'List id "' . self::LIST_ID . '" is not known', 2);
        
        $this->callListener(array(
            'data' => array(
                'list_id' => self::LIST_ID,
                'email' => 'user@example.com'
            )
        )); 
    }
}