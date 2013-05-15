<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class UpdateActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }
    
    public function testWithoutBeingLoggedIn() {
        $response = $this->getUserAccountDetailsController('updateAction')->updateAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());          
    }    
    
    public function testWithNoRequestValues() {        
        $this->setUser($this->makeUser());
        
        $response = $this->getUserAccountDetailsController('updateAction')->updateAction();
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('show-help', $this->container->get('session')->getFlash('user_account_details_update_notice'));
    }
    
    
    public function testWithInvalidNewEmail() {        
        $this->setUser($this->makeUser());        
        
        $newEmail = 'not-an-email-address';
        
        $response = $this->getUserAccountDetailsController('updateAction', array(
            'email' => $newEmail
        ))->updateAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('invalid-email', $this->container->get('session')->getFlash('user_account_details_update_notice'));
        $this->assertEquals($newEmail, $this->container->get('session')->getFlash('user_account_details_update_email'));
    } 
    
    public function testUpdateEmailToCurrentEmail () {
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $response = $this->getUserAccountDetailsController('updateAction', array(
            'email' => $user->getUsername()
        ))->updateAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('same-email', $this->container->get('session')->getFlash('user_account_details_update_notice'));
    }    
    
    
    public function testWithEmailAlreadyInUse() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $newEmail = 'user2@example.com';
        
        $response = $this->getUserAccountDetailsController('updateAction', array(
            'email' => $newEmail
        ))->updateAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('email-taken', $this->container->get('session')->getFlash('user_account_details_update_notice'));
        $this->assertEquals($newEmail, $this->container->get('session')->getFlash('user_account_details_update_email'));        
    }
    
    public function testWithUnknownErrorCommunicatingWithCoreApplication() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $newEmail = 'user2@example.com';
        
        $response = $this->getUserAccountDetailsController('updateAction', array(
            'email' => $newEmail
        ))->updateAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('unknown', $this->container->get('session')->getFlash('user_account_details_update_notice'));
    }    
    
    public function testWithSuccessfulEmailChangeRequestCreation() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $newEmail = 'user2@example.com';
        
        $response = $this->getUserAccountDetailsController('updateAction', array(
            'email' => $newEmail
        ))->updateAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
        
        $this->assertEquals('done', $this->container->get('session')->getFlash('user_account_details_update_notice'));        
    }

}


 