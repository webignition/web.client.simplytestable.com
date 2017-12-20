<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnUpEmail\ValidListId;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\MailChimp\OnUpEmail\OnUpEmailTest;

class InvalidListIdTest extends OnUpEmailTest {    
    
    const LIST_ID  = 'foo';
    
    public function testDomainExceptionIsThrown() {
        $this->setExpectedException('\DomainException', 'List id "' . self::LIST_ID . '" is not known', 2);
        
        $this->callListener(array(
            'data' => array(
                'list_id' => self::LIST_ID,
                'old_email' => 'user@example.com',
                'new_email' => 'user@example.com'
            )
        )); 
    }
}