<?php

namespace SimplyTestable\WebClientBundle\Tests\Model;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;
use SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome;

class ControllerTestRetrievalOutcomeTest extends BaseTestCase {   
    
    public function testNoResponse() {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertFalse($outcome->hasResponse());
    }
    
    public function testNoTest() {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertFalse($outcome->hasTest());
    }    
    
    public function testSetHasResponse() {
        $outcome = new ControllerTestRetrievalOutcome();        
        $this->assertTrue($outcome->setResponse(new \Symfony\Component\HttpFoundation\Response())->hasResponse());
    }
    
    public function testSetHasTest() {
        $outcome = new ControllerTestRetrievalOutcome();        
        $this->assertTrue($outcome->setTest(new \SimplyTestable\WebClientBundle\Entity\Test\Test())->hasTest());
    }
    
}