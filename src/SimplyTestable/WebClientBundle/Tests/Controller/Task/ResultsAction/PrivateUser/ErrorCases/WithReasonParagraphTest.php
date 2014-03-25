<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

abstract class WithReasonParagraphTest extends ErrorCasesTest {
    
    abstract protected function getExpectedReasonParagraphText();
    
    public function testReasonParagraphPresent() {        
        $reasonParagraphs = $this->getScopedCrawler()->filter('#reason');        
        $this->assertEquals(1, $reasonParagraphs->count(), 'Reason paragraph not found');        
    }
    
    public function testReasonParagraphContent() {        
        $reasonParagraphs = $this->getScopedCrawler()->filter('#reason');        
        
        foreach ($reasonParagraphs as $reasonParagraph) {
            $this->assertDomNodeContainsNext($reasonParagraph, $this->getExpectedReasonParagraphText());
        }     
    }

}


