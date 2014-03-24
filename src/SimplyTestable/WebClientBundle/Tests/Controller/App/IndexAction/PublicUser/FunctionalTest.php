<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction\PublicUser;

use SimplyTestable\WebClientBundle\Tests\Controller\App\IndexAction\FunctionalTest as BaseFunctionalTest;

class FunctionalTest extends BaseFunctionalTest {
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }
    
    public function testNavbarContainsSignInButton() {
        $this->publicUserNavbarContainsSignInButtonTest($this->getCrawler($this->getCurrentRequestUrl()));          
    }
    
    public function testNavbarSignInButtonUrl() {  
        $this->publicUserNavbarSignInButtonUrlTest($this->getCrawler($this->getCurrentRequestUrl()));          
    }      
    
    public function testNavbarContainsCreateAccountButton() { 
        $this->publicUserNavbarContainsCreateAccountButtonTest($this->getCrawler($this->getCurrentRequestUrl()));          
    }
    
    public function testNavbarCreateAccountButtonUrl() {  
        $this->publicUserNavbarCreateAccountButtonUrlTest($this->getCrawler($this->getCurrentRequestUrl()));          
    }
    
    public function testFreeDemoNotificationIsPresent() {        
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        $crawler = $this->getCrawler($this->getCurrentRequestUrl());
        
        $fullWidthNotifications = $crawler->filter('.full-width-notification');
        
        $this->assertGreaterThan(0, $fullWidthNotifications->count());

        foreach ($fullWidthNotifications as $fullWidthNotification) {
            $this->assertDomNodeContainsNext($fullWidthNotification, 'limited free demo');
        }
    }
    
    
    public function testShortUrlWebsiteIsPresentInWebsiteInputField() {
        $websiteUrl = 'http://example.com/';
        
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        $crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => $websiteUrl
        )));
        
        $websiteInputs = $crawler->filter('#input-website');
        
        $this->assertEquals(1, $websiteInputs->count());
        
        $inputValues = $websiteInputs->first()->extract('value');
        
        $this->assertEquals($websiteUrl, $inputValues[0]);      
    }
    
    public function testLongUrlWebsiteIsPresentInWebsiteInputField() {
        $websiteUrl = 'http://example.com/012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456';
        
        /* @var $navbar \Symfony\Component\DomCrawler\Crawler */
        $crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
            'website' => $websiteUrl
        )));
        
        $websiteInputs = $crawler->filter('#input-website');
        
        $this->assertEquals(1, $websiteInputs->count());
        
        $inputValues = $websiteInputs->first()->extract('value');
        
        $this->assertEquals($websiteUrl, $inputValues[0]);      
    }    

}


