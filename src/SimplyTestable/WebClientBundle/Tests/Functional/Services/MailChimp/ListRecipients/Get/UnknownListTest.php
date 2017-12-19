<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\Get;

use SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients\ServiceTest;

class UnknownListTest extends ServiceTest {
    
    public function testGetUnknownList() {
        $name = 'foo';        
        $this->setExpectedException('DomainException', 'List "' . $name . '" is not known', 1);
        $this->getMailChimpListRecipientsService()->get($name);
    }    

}
