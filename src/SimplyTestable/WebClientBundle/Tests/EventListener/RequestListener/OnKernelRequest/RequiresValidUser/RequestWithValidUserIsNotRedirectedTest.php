<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresValidUser;

class RequestWithValidUserIsNotRedirectedTest extends RequiresValidUserTest {

    protected function getUserAuthenticateHttpResponse() {
        return 'HTTP/1.1 200';
    }

    public function testEventHasNoResponse() {
        $this->assertNull($this->getEvent()->getResponse());
    }

}