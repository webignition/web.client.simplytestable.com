<?php

namespace SimplyTestable\WebClientBundle\Tests\Services;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class MailchimpServiceTest extends BaseSimplyTestableTestCase {   
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }
    
    public function testListDoesContain() {        
        $this->assertTrue($this->getMailchimpService()->listContains('updates', 'jon@webignition.net'));
    }
    

    public function testListDoesNotContain() {        
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
