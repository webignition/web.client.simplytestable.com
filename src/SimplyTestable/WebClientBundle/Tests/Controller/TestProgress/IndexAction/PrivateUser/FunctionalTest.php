<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\IndexAction\PrivateUser;

use SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghij';

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
    
    public function testNavbarSignoutFormUrl() {
        $this->privateUserNavbarSignOutFormUrlTest($this->getScopedCrawler());          
    }
    
    public function testLongUrlIsPresentInPageTitle() {        
        $this->assertTitleContainsText($this->getScopedCrawler(), str_replace('http://', '', self::WEBSITE));        
    }    
    
    /**
     * 
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getScopedCrawler() {        
        return $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => self::WEBSITE,
            'test_id' => 1
        )));        
    }

}


