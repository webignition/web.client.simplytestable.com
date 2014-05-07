<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresUser;

class RequestWithoutUserIsRedirectedTest extends RequiresUserTest {

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