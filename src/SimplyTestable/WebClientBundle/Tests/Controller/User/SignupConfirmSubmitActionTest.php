<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class SignupConfirmSubmitActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    } 
    
    public function testWithNonExistentUser() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmSubmitAction')->signupConfirmSubmitAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-user', $this->container->get('session')->getFlash('token_resend_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());
    } 
    
    
    public function testWithBlankToken() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmSubmitAction', array(
            'token' => ''
        ))->signupConfirmSubmitAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('blank-token', $this->container->get('session')->getFlash('user_token_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());
    }  
    
    
    public function testWithFailedDueToReadOnly() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmSubmitAction', array(
            'token' => 'valid-token'
        ))->signupConfirmSubmitAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('failed-read-only', $this->container->get('session')->getFlash('user_token_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());       
    }     
    
    
    public function testWithInvalidToken() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmSubmitAction', array(
            'token' => 'invalid-token'
        ))->signupConfirmSubmitAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-token', $this->container->get('session')->getFlash('user_token_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());       
    }  
    
    public function testWithInvalidAdminCredentials() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $this->getUserController('signupConfirmSubmitAction', array(
                'token' => 'valid-token'
            ))->signupConfirmSubmitAction('user@example.com');
            $this->fail('CoreApplicationAdminRequestException  401 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };         
    }
    
    public function testSuccess() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signupConfirmSubmitAction', array(
                'token' => 'valid-token'
            ))->signupConfirmSubmitAction('user@example.com');
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());        
    }
    

}


 