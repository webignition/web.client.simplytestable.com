<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class ConfirmEmailChangeActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }
    
    public function testWithoutBeingLoggedIn() {
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction')->confirmEmailChangeAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());          
    } 
    
    
    public function testResendAction() {
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            're-send' => 1
        ))->confirmEmailChangeAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('re-sent', $this->container->get('session')->getFlash('user_account_details_confirm_email_change_notice'));        
    }
    
    public function testCancelAction() {
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'cancel' => 1
        ))->confirmEmailChangeAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('cancelled', $this->container->get('session')->getFlash('user_account_details_confirm_email_change_notice'));        
    }
    
    
    public function testConfirmActionWithBlankToken() {
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'confirm' => 1
        ))->confirmEmailChangeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('invalid-token', $this->container->get('session')->getFlash('user_account_details_update_email_confirm_notice'));                
    }
    
    public function testConfirmActionWithInvalidToken() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'confirm' => 1,
            'token' => 'this-is-not-the-right-token'
        ))->confirmEmailChangeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('invalid-token', $this->container->get('session')->getFlash('user_account_details_update_email_confirm_notice'));                
    }    
    
    public function testConfirmActionWhenEmailHasSinceBeenTaken() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'confirm' => 1,
            'token' => 'valid-token'
        ))->confirmEmailChangeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('email-taken', $this->container->get('session')->getFlash('user_account_details_update_email_confirm_notice'));                
        $this->assertEquals('user2@example.com', $this->container->get('session')->getFlash('user_account_details_update_email'));                
    }  
    
    
    public function testConfirmActionWithUnknownErrorCommunicatingWithCoreApplication() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'confirm' => 1,
            'token' => 'valid-token'
        ))->confirmEmailChangeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('unknown', $this->container->get('session')->getFlash('user_account_details_update_email_confirm_notice'));
    } 
    
    public function testConfirmActionSuccess() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $user = $this->makeUser();
        $this->setUser($user);
        
        $response = $this->getUserAccountDetailsController('confirmEmailChangeAction', array(
            'confirm' => 1,
            'token' => 'valid-token'
        ))->confirmEmailChangeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath()); 
        
        $this->assertEquals('success', $this->container->get('session')->getFlash('user_account_details_update_email_confirm_notice'));
    }
    
} 