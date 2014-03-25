<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\FailedNoUrlsDetectedAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestResults\FunctionalTest as BaseFunctionalTest;

class FunctionalTest extends BaseFunctionalTest {
    
    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    
    private $crawler;
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }
    
    protected function getRoute() {
        return 'app_results_failed_no_urls_detected';
    }     
    
    protected function getActionName() {
        return 'failedNoUrlsDetectedAction';
    }    
    
    public function testNavbarContainsSignInButton() {        
        $this->publicUserNavbarContainsSignInButtonTest($this->getScopedCrawler());          
    }
    
    public function testNavbarSignInButtonUrl() {  
        $this->publicUserNavbarSignInButtonUrlTest($this->getScopedCrawler());          
    }      
    
    public function testNavbarContainsCreateAccountButton() { 
        $this->publicUserNavbarContainsCreateAccountButtonTest($this->getScopedCrawler());          
    }
    
    public function testNavbarCreateAccountButtonUrl() {  
        $this->publicUserNavbarCreateAccountButtonUrlTest($this->getScopedCrawler());          
    }    

    public function testLongUrlInPageTitleIsTruncated() {
        $expectedTitleUrl = substr(str_replace('http://', '', self::WEBSITE), 0, 64) . '…';
        
        $this->assertTitleContainsText($this->getScopedCrawler(), $expectedTitleUrl);
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


