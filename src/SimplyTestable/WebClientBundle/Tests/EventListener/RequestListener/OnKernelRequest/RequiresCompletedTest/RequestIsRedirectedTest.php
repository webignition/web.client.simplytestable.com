<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresCompletedTest;

abstract class RequestIsRedirectedTest extends RequiresCompletedTestTest {

    public function testEventHasResponse() {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->getEvent()->getResponse());
    }

    public function testHasRedirectResponse() {
        $this->assertEquals(302, $this->getEvent()->getResponse()->getStatusCode());
    }

}