<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        $this->setUser(new User('user@example.com','password'));
    }
    
    public function testNavbarContainsAccountLink() {                
        $this->privateUserNavbarContainsAccountLinkTest($this->getScopedCrawler());           
    }
    
    public function testNavbarAccountLinkUrl() {                
        $this->privateUserNavbarAccountLinkUrlTest($this->getScopedCrawler());           
    }
    
    public function testNavbarContainsSignOutButton() {
        $this->privateUserNavbarContainsSignOutButtonTest($this->getScopedCrawler());          
    }
    
    public function privateUserNavbarSignOutFormUrlTest() {
        $this->privateUserNavbarSignOutButtonUrlTest($this->getScopedCrawler());          
    }
    
    /**
     * 
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getScopedCrawler() {        
        return $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => 'http://example.com',
            'test_id' => 1,
            'task_id' => 1
        )));        
    }

}


