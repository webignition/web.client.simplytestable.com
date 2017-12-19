<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\MailChimp\ListRecipients;

class GetListNamesTest extends ServiceTest {
    
    public function testGetListNames() {
        $this->assertEquals(array(
            'announcements',
            'updates',
            'introduction'
        ), $this->getMailChimpListRecipientsService()->getListNames());
    }    

}
