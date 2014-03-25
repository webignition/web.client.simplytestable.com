<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

abstract class WithReasonParagraphAndLinkTest extends WithReasonParagraphTest {
    
    public function testReasonParagraphContainsLink() {
        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');
        
        $this->assertEquals(1, $reasonParagraphLinks->count(), "Reason paragraph does not contain link");      
    }
    
    public function testReasonParagraphLinkHref() {
        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');   
        
        $firstReasonParagraphHrefs = $reasonParagraphLinks->first()->extract('href');
        $this->assertEquals(self::WEBSITE, $firstReasonParagraphHrefs[0]);       
    }    
    
    public function testReasonParagraphLinkContent() {
        $reasonParagraphLinks = $this->getScopedCrawler()->filter('#reason')->filter('a');
        
        foreach ($reasonParagraphLinks as $reasonParagraphLink) {
            $this->assertDomNodeContainsNext($reasonParagraphLink, 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrs…');
        }          
    }

}


