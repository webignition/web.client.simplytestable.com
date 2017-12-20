<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\RequiresValidUser;

class RequestWithInvalidUserIsRedirectedTest extends RequiresValidUserTest {

    protected function getUserAuthenticateHttpResponse() {
        return 'HTTP/1.1 404';
    }

    public function testEventHasResponse() {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->getEvent()->getResponse());
    }

    public function testHasRedirectResponse() {
        $this->assertEquals(302, $this->getEvent()->getResponse()->getStatusCode());
    }

    public function testRedirectIsForSignInPage() {
        $expectedRedirectUrl = $this->container->get('router')->generate('sign_out_submit', array(), true);

        $this->assertEquals($expectedRedirectUrl, $this->getEvent()->getResponse()->headers->get('location'));
    }

}