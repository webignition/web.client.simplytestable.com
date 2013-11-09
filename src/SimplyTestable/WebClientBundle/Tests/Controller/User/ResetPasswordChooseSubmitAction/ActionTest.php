<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\ResetPasswordChooseSubmitAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {     
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }     
    
    protected function getActionName() {
        return 'resetPasswordChooseSubmitAction';
    }    

    
    public function testWithBlankEmail() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/'
        ));              
    }
    
    public function testWithInvalidEmailAndNonBlankToken() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/'
        ), array(
            'postData' => array(
                'email' => 'foobar',
                'token' => 'non-blank-token'                
            )
        ));                
    }    
    
    public function testWithNonExistentUserAndNonBlankToken() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/'
        ), array(
            'postData' => array(
                'email' => 'nonexistent-user@example.com',
                'token' => 'non-blank-token'                 
            )
        ));        
    }    
    
    public function testWithInvalidTokenPreReset() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/nonexistent-user@example.com/non-blank-token/',
            'flash' => array(
                'user_reset_password_error' => 'invalid-token'
            )
        ), array(
            'postData' => array(
                'email' => 'nonexistent-user@example.com',
                'token' => 'non-blank-token'                 
            )
        ));                  
    }    
    
    public function testWithBlankPassword() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/user@example.com/valid-token/',
            'flash' => array(
                'user_reset_password_error' => 'blank-password'
            )
        ), array(
            'postData' => array(
                'email' => 'user@example.com',
                'token' => 'valid-token'                 
            )
        ));            
    }
    
    
    public function testWithValidEmailAndValidTokenAndFailedDueToReadOnly() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/user@example.com/valid-token/',
            'flash' => array(
                'user_reset_password_error' => 'failed-read-only'
            )
        ), array(
            'postData' => array(
                'email' => 'user@example.com',
                'token' => 'valid-token',
                'password' => 'non-blank-password'              
            )
        ));       
    }  
    
    public function testWithInvalidTokenAtResetTime() {
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'non-blank-password',
            'token' => 'valid-token'            
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode()); 
        
        $this->assertEquals('invalid-token', $this->container->get('session')->getFlash('user_reset_password_error'));
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/user@example.com/valid-token/', $responseUrl->getPath());          
    }
    
    public function testWithValidEmailAndValidToken() {
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'user@example.com',
            'token' => 'valid-token',
            'password' => 'non-blank-password'
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode()); 

        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/', $responseUrl->getPath());        
    }



}


 