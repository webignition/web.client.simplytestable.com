<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\RequiresValidTestOwner;

class RequestWithInvalidOwnerIsRedirectedTest extends RequiresValidTestOwnerTest {

    protected function getRemoteTestSummaryHttpResponse() {
        return 'HTTP/1.1 403';
    }

    public function testEventHasResponse() {
        $this->assertInstanceOf('Symfony\Component\HttpFoundation\Response', $this->getEvent()->getResponse());
    }

    public function testHasRedirectResponse() {
        $this->assertEquals(302, $this->getEvent()->getResponse()->getStatusCode());
    }

    public function testRedirectIsForSignInPage() {
        $expectedRedirectUrl = $this->container->get('router')->generate('view_user_signin_index', array(
            'redirect' => base64_encode(json_encode(array(
                'route' => 'view_test_progress_index_index',
                'parameters' => array(
                    'website' => self::WEBSITE,
                    'test_id' => self::TEST_ID
                )
            )))
        ), true);

        $this->assertEquals($expectedRedirectUrl, $this->getEvent()->getResponse()->headers->get('location'));
    }

}