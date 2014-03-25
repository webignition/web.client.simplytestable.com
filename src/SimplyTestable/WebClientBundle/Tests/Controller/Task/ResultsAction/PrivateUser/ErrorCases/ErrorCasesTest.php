<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\FunctionalTest as BaseFunctionalTest;
use SimplyTestable\WebClientBundle\Model\User;

abstract class ErrorCasesTest extends BaseFunctionalTest {    
    
    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    
    private $crawler;
    
    public function setUp() {
        parent::setUp();
        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        $this->setUser(new User('user@example.com','password'));
    }
    
//    abstract protected function getExpectedReasonParagraphText();
//    
//    public function testReasonParagraphPresent() {        
//        $reasonParagraphs = $this->getScopedCrawler()->filter('#reason');        
//        $this->assertEquals(1, $reasonParagraphs->count(), 'Reason paragraph not found');        
//    }
//    
//    public function testReasonParagraphContent() {        
//        $reasonParagraphs = $this->getScopedCrawler()->filter('#reason');        
//        
//        foreach ($reasonParagraphs as $reasonParagraph) {
//            $this->assertDomNodeContainsNext($reasonParagraph, $this->getExpectedReasonParagraphText());
//        }     
//    }
//    
//    public function testReasonParagraphContainsLink() {
//        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');
//        
//        $this->assertEquals(1, $reasonParagraphLinks->count());      
//    }
//    
//    public function testReasonParagraphLinkHref() {
//        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');   
//        
//        $firstReasonParagraphHrefs = $reasonParagraphLinks->first()->extract('href');
//        $this->assertEquals(self::WEBSITE, $firstReasonParagraphHrefs[0]);       
//    }    
//    
//    public function testReasonParagraphLinkContent() {
//        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');
//        
//        foreach ($reasonParagraphLinks as $reasonParagraphLink) {
//            $this->assertDomNodeContainsNext($reasonParagraphLink, 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrs…');
//        }          
//    }
    
    /**
     * 
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    protected function getScopedCrawler() {        
        if (!isset($this->crawler)) {
            $this->crawler = $this->getCrawler($this->getCurrentRequestUrl(array(
                'website' => self::WEBSITE,
                'test_id' => 1,
                'task_id' => 2
            )));
        }
        
        return $this->crawler;
    }

}


