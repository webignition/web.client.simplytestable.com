<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\IndexAction\PrivateUser;

use SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

abstract class FunctionalTest extends BaseFunctionalTest {
    
    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    
    private $crawler;

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
    
    public function testLongUrlInPageTitleIsTruncated() {
        $expectedTitleUrl = substr(str_replace('http://', '', self::WEBSITE), 0, 64) . '…';
        
        $this->assertTitleContainsText($this->getScopedCrawler(), $expectedTitleUrl);
    }
    
    public function testFullTestUrlIsPresentInHeadingTitle() {
        $titles = $this->getScopedCrawler()->filter('#test-url')->extract('title');
        $this->assertEquals(self::WEBSITE, $titles[0]);
    }    
    
    /**
     * 
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    private function getScopedCrawler() {        
        if (is_null($this->crawler)) {
            $this->crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
                'website' => self::WEBSITE,
                'test_id' => 1
            )));  
        }
        
        return $this->crawler;
    }  

}


