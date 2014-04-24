<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\RejectedAction\FunctionalTest;

class FullSiteJobsPerSiteTest extends FunctionalTest {
    
    const WEBSITE = 'http://example.com/';
    
    protected function getWebsite() {
        return self::WEBSITE;
    }
    
    public function testHasRejectionReason() {
        $reasons = $this->getScopedCrawler()->filter('#reason');
        $this->assertEquals(1, $reasons->count());  
    }
    
    public function testRejectionReasonContent() {
        $reasons = $this->getScopedCrawler()->filter('#reason');
        
        foreach ($reasons as $reason) {            
            $this->assertDomNodeContainsNext($reason, 'reached the limit of free full-site');
        } 
    }

}


