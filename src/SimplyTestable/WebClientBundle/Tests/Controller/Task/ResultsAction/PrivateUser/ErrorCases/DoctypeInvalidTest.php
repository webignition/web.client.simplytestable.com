<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\ResultsAction\PrivateUser\ErrorCases;

class DoctypeInvalidTest extends WithReasonParagraphAndLinkTest {    
    
    protected function getExpectedReasonParagraphText() {
        return "contains an invalid doctype";
    }
    
    public function testDoctypeNoticeIsPresent() {
        $pres = $this->getScopedCrawler()->filter('pre');        
        
        $this->assertEquals(1, $pres->count(), "Pre notice missing");
    }    
    
    public function testDoctypeNoticeContent() {
        $pres = $this->getScopedCrawler()->filter('pre');        
        
        foreach ($pres as $pre) {
            $this->assertDomNodeContainsNext($pre, 'foobar');
        }
    }

}


