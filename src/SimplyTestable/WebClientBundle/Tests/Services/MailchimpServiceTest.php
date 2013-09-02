<?php

namespace SimplyTestable\WebClientBundle\Tests\Services;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class MailchimpServiceTest extends BaseSimplyTestableTestCase {   
    
    
    public function testListDoesContain() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')), $this->getMailchimpService()->getClient());        
        $this->assertTrue($this->getMailchimpService()->listContains('updates', 'jon@webignition.net'));
    }
    

    public function testListDoesNotContain() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')), $this->getMailchimpService()->getClient());        
        $this->assertFalse($this->getMailchimpService()->listContains('updates', 'foo@bar.com'));
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\MailchimpService
     */
    private function getMailchimpService() {
        return $this->container->get('simplytestable.services.mailchimpservice');
    }    

}
