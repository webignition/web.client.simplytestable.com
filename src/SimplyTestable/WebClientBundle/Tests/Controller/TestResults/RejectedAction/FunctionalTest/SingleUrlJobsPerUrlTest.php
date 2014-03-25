<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestResults\RejectedAction\FunctionalTest;

class SingleUrlJobsPerUrlTest extends FunctionalTest {
    
    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    
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
            $this->assertDomNodeContainsNext($reason, 'There have already been 10 free single-URL tests this month for ' . substr($this->getWebsite(), 0, 40) . '…');
        }
    }

}


