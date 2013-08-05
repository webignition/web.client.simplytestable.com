<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class SignupSubmitActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    } 
    
    
    public function testWithBlankEmail() {        
        $response = $this->getUserController('signUpSubmitAction')->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('blank-email', $this->container->get('session')->getFlash('user_create_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());
    }
    
    public function testWithInvalidEmail() {        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'foobar'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-email', $this->container->get('session')->getFlash('user_create_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());
    } 
    
    public function testWithBlankPassword() {        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('blank-password', $this->container->get('session')->getFlash('user_create_error'));
        $this->assertEquals('user@example.com', $this->container->get('session')->getFlash('user_create_prefil'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());
    }    
    
    public function testWithPreExistingUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'password'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('user-exists', $this->container->get('session')->getFlash('user_create_confirmation'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());        
    }

    public function testWithFailedDueToReadOnly() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'password'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('create-failed-read-only', $this->container->get('session')->getFlash('user_create_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());        
    }     

    
    public function testWithFailedDueToHttpServerError() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'password'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('create-failed', $this->container->get('session')->getFlash('user_create_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());        
    }     
    
    
    public function testWithFailedDueToHttpClientError() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'password'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('create-failed', $this->container->get('session')->getFlash('user_create_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/', $responseUrl->getPath());        
    }      
    
    
    public function testWithInvalidAdminCredentials() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $response = $this->getUserController('signUpSubmitAction', array(
                'email' => 'user@example.com',
                'password' => 'password'
            ))->signUpSubmitAction();
            $this->assertEquals(302, $response->getStatusCode());
            $this->fail('CoreApplicationAdminRequestException  401 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };       
    }    
    
    
    public function testSuccess() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signUpSubmitAction', array(
            'email' => 'user@example.com',
            'password' => 'password'
        ))->signUpSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signup/confirm/user@example.com/', $responseUrl->getPath());        
    }
    

}


 