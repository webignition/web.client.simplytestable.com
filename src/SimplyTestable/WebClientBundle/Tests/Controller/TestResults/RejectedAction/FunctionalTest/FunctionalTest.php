<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\RejectedAction\FunctionalTest;

use SimplyTestable\WebClientBundle\Tests\Controller\TestResults\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {
    
    private $crawler;
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }
    
    abstract protected function getWebsite();
    
    protected function getRoute() {
        return 'view_test_results_rejected_index_index';
    }     
    
    protected function getActionName() {
        return 'rejectedAction';
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
    
    
    /**
     * 
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getScopedCrawler() {        
        if (is_null($this->crawler)) {
            $this->crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
                'website' => $this->getWebsite(),
                'test_id' => 1
            )));  
        }
        
        return $this->crawler;
    }    

}


