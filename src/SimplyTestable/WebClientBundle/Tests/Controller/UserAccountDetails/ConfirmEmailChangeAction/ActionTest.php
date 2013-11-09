<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails\ConfirmEmailChangeAction;

use SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }    
    
    protected function getActionName() {
        return 'confirmEmailChangeAction';
    } 
    
    public function testWithoutBeingLoggedIn() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));               
    } 
    
    
    public function testResendAction() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_confirm_email_change_notice' => 're-sent'
            )
        ), array(
            'postData' => array(
                're-send' => 1
            )
        ));
    }
    
    public function testCancelAction() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_confirm_email_change_notice' => 'cancelled'
            )
        ), array(
            'postData' => array(
                'cancel' => 1
            )
        ));
    }
    
    
    public function testConfirmActionWithBlankToken() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_email_confirm_notice' => 'invalid-token'
            )
        ), array(
            'postData' => array(
                'confirm' => 1
            )
        ));
    }
    
    public function testConfirmActionWithInvalidToken() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_email_confirm_notice' => 'invalid-token'
            )
        ), array(
            'postData' => array(
                'confirm' => 1,
                'token' => 'this-is-not-the-right-token'
            )
        ));
    }    
    
    public function testConfirmActionWhenEmailHasSinceBeenTaken() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_email_confirm_notice' => 'email-taken',
                'user_account_details_update_email' => 'user2@example.com'
            )
        ), array(
            'postData' => array(
                'confirm' => 1,
                'token' => 'valid-token'
            )
        ));           
    }  
    
    
    public function testConfirmActionWithUnknownErrorCommunicatingWithCoreApplication() {
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_email_confirm_notice' => 'unknown'               
            )
        ), array(
            'postData' => array(
                'confirm' => 1,
                'token' => 'valid-token'
            )
        ));
    } 
    
    public function testConfirmActionSuccess() {
        $user = $this->makeUser();
        $this->setUser($user);
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_email_confirm_notice' => 'success'               
            )
        ), array(
            'postData' => array(
                'confirm' => 1,
                'token' => 'valid-token'
            )
        ));
    }
    
} 