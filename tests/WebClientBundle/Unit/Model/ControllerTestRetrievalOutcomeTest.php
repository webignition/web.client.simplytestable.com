<?php

namespace Tests\WebClientBundle\Unit\Model;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\ControllerTestRetrievalOutcome;
use Symfony\Component\HttpFoundation\Response;

class ControllerTestRetrievalOutcomeTest extends \PHPUnit_Framework_TestCase
{
    public function testNoResponse()
    {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertFalse($outcome->hasResponse());
    }

    public function testNoTest()
    {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertFalse($outcome->hasTest());
    }

    public function testSetHasResponse()
    {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertTrue($outcome->setResponse(new Response())->hasResponse());
    }

    public function testSetHasTest()
    {
        $outcome = new ControllerTestRetrievalOutcome();
        $this->assertTrue($outcome->setTest(new Test())->hasTest());
    }
}
