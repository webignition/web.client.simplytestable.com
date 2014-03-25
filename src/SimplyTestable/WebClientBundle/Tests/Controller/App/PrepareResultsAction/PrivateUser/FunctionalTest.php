<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\PrepareResultsAction\PrivateUser;

use SimplyTestable\WebClientBundle\Tests\Controller\App\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

class FunctionalTest extends BaseFunctionalTest {    
    
    const WEBSITE = 'http://example.com/012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456';
    
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
    
    public function testContentWebsiteIsPresent() {
        $contentWebsites = $this->getScopedCrawler()->filter('#website');
        $this->assertEquals(1, $contentWebsites->count());
    }
    
    public function testContentWebsiteTitle() {
        $contentWebsites = $this->getScopedCrawler()->filter('#website');
        $titles = $contentWebsites->extract('title');
        
        $this->assertEquals(self::WEBSITE, $titles[0]);
    }    
    
    public function testContentWebsiteContent() {
        $contentWebsites = $this->getScopedCrawler()->filter('#website');
        $expectedWebsiteContent = substr(self::WEBSITE, 0, 40) . '…'; 

        foreach ($contentWebsites as $contentWebsite) {
            $this->assertDomNodeContainsNext($contentWebsite, $expectedWebsiteContent);
        }
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


