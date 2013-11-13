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
        
        $hasFreeDemoNotification = false;
        foreach ($fullWidthNotifications as $fullWidthNotification) {
            if (substr_count($this->domNodeToHtml($fullWidthNotification), 'limited free demo')) {
                $hasFreeDemoNotification = true;
            }
        }
        
        $this->assertTrue($hasFreeDemoNotification);   
    }       

}


