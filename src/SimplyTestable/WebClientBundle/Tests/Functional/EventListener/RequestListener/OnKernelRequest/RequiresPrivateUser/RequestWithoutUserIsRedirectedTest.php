<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\RequiresPrivateUser;

class RequestWithoutUserIsRedirectedTest extends RequiresPrivateUserTest {

    protected function getHttpFixtureItems() {
        return [
            'HTTP/1.0 200 OK'
        ];
    }

    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\View\User\Account\IndexController::indexAction';
    }
    
    protected function getControllerRouteString() {
        return 'view_user_account_index_index';
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
                'route' => 'view_user_account_index_index')
            ))            
        ), true);
        
        $this->assertEquals($expectedRedirectUrl, $this->getEvent()->getResponse()->headers->get('location'));
    }
    

}