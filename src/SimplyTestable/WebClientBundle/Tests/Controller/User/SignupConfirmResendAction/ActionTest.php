<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\SignUpConfirmResendAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {
    
    protected function getActionName() {
        return 'signupConfirmResendAction';
    }    
    
    public function testWithNonExistentUser() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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


 