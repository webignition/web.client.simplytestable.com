<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class SignInSubmitActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    } 

    
    public function testWithBlankEmail() {        
        $response = $this->getUserController('signInSubmitAction')->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('blank-email', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());
    }    
    
    public function testWithInvalidEmail() {        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'foobar'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-email', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());
    }     
    
    public function testWithBlankPassword() {        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'user@example.com'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('blank-password', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());
    }     
    
    
    public function testWithPublicUser() {        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'public@simplytestable.com',
            'password' => 'public'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('public-user', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());
    } 
    
    
    public function testWithInvalidUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'invalid-user@example.com',
            'password' => 'password'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('invalid-user', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());        
    }   
    
    
    public function testWithInvalidCredentials() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'invalid-user@example.com',
            'password' => 'password'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('authentication-failure', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());        
    }    
    
    
    public function testWithInvalidUserThatIsNotEnabled() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'invalid-user@example.com',
            'password' => 'password'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('user-not-enabled', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());        
    }    
    
    public function testWithValidUserThatIsNotEnabled() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'valid-user@example.com',
            'password' => 'password'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('user-not-enabled', $this->container->get('session')->getFlash('user_signin_error'));
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());        
    } 
    
    
    public function testWithValidUserThatIsEnabled() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('signInSubmitAction', array(
            'email' => 'valid-user@example.com',
            'password' => 'password'
        ))->signInSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/', $responseUrl->getPath());        
    } 

}


 