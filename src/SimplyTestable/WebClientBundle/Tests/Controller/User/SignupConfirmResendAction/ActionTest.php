<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\SignupConfirmResendAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }       
    
    protected function getActionName() {
        return 'signupConfirmResendAction';
    }    
    
    public function testWithNonExistentUser() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/',
            'flash' => array(
                'token_resend_error' => 'invalid-user'
            )
        ), array(
            'methodArguments' => array(
                'email' => 'user@example.com'
            )
        ));
    } 
    
    
    public function testWithInvalidAdminCredentials() {
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'email' => 'user@example.com'
                )
            ));
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };          
    }
    
    
    public function testSuccess() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/',
            'flash' => array(
                'token_resend_confirmation' => 'sent'
            )
        ), array(
            'methodArguments' => array(
                'email' => 'user@example.com'
            )
        ));       
    }
    

}


 