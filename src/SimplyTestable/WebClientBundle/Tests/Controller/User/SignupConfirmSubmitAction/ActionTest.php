<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\SignupConfirmSubmitAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    protected function getActionName() {
        return 'signupConfirmSubmitAction';
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
    
    
    public function testWithBlankToken() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/',
            'flash' => array(
                'user_token_error' => 'blank-token'
            )
        ), array(            
            'methodArguments' => array(
                'email' => 'user@example.com'
            ),
            'postData' => array(
                'token' => ''
            )
        ));
    }  
    
    
    public function testWithFailedDueToReadOnly() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/',
            'flash' => array(
                'user_token_error' => 'failed-read-only'
            )
        ), array(            
            'methodArguments' => array(
                'email' => 'user@example.com'
            ),
            'postData' => array(
                'token' => 'valid-token'
            )
        ));     
    }     
    
    
    public function testWithInvalidToken() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/',
            'flash' => array(
                'user_token_error' => 'invalid-token'
            )
        ), array(            
            'methodArguments' => array(
                'email' => 'user@example.com'
            ),
            'postData' => array(
                'token' => 'invalid-token'
            )
        ));      
    }  
    
    public function testWithInvalidAdminCredentials() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $this->performActionTest(array(), array(            
                'methodArguments' => array(
                    'email' => 'user@example.com'
                ),
                'postData' => array(
                    'token' => 'valid-token'
                )
            ));
            $this->fail('CoreApplicationAdminRequestException  401 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };         
    }
    
    public function testSuccess() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(            
            'methodArguments' => array(
                'email' => 'user@example.com'
            ),
            'postData' => array(
                'token' => 'valid-token'
            )
        ));       
    }
    

}


 