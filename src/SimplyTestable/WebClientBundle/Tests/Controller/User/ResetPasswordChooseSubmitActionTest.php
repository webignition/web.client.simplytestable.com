<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class ResetPasswordChooseSubmitActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    } 
    
    public function testWithBlankEmail() {
        $response = $this->getUserController('resetPasswordChooseSubmitAction')->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/', $responseUrl->getPath());        
    }
    
    public function testWithInvalidEmailAndNonBlankToken() {
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'foobar',
            'token' => 'non-blank-token'
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/', $responseUrl->getPath());                
    }    
    
    public function testWithNonExistentUserAndNonBlankToken() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'nonexistent-user@example.com',
            'token' => 'non-blank-token'            
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/', $responseUrl->getPath());         
    }    
    
    public function testWithInvalidTokenPreReset() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'nonexistent-user@example.com',
            'token' => 'non-blank-token'            
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode()); 
        
        $this->assertEquals('invalid-token', $this->container->get('session')->getFlash('user_reset_password_error'));
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/nonexistent-user@example.com/non-blank-token/', $responseUrl->getPath());          
    }    
    
    public function testWithBlankPassword() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'user@example.com',
            'token' => 'valid-token'            
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode()); 
        
        $this->assertEquals('blank-password', $this->container->get('session')->getFlash('user_reset_password_error'));
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/user@example.com/valid-token/', $responseUrl->getPath());             
    }
    
    
    public function testWithValidEmailAndValidTokenAndFailedDueToReadOnly() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getUserController('resetPasswordChooseSubmitAction', array(
            'email' => 'user@example.com',
            'token' => 'valid-token',
            'password' => 'non-blank-password'
        ))->resetPasswordChooseSubmitAction();
        $this->assertEquals(302, $response->getStatusCode()); 

        $this->assertEquals('failed-read-only', $this->container->get('session')->getFlash('user_reset_password_error'));
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/reset-password/user@example.com/valid-token/', $responseUrl->getPath());        
    }  
    
    public function testWithInvalidTokenAtResetTime() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
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


 