<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails\UpdateAction;

use SimplyTestable\WebClientBundle\Tests\Controller\UserAccountDetails\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    protected function getActionName() {
        return 'updateAction';
    } 
    
    public function testWithoutBeingLoggedIn() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));       
    }    
    
    public function testWithNoRequestValues() {        
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'show-help'
            )
        ));
    }
    
    
    public function testWithInvalidNewEmail() {        
        $this->setUser($this->makeUser());        
        $newEmail = 'not-an-email-address';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'invalid-email',
                'user_account_details_update_email' => $newEmail
            )
        ), array(
            'postData' => array(
                'email' => $newEmail
            )
        ));
    } 
    
    public function testUpdateEmailToCurrentEmail () {
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'same-email'
            )
        ), array(
            'postData' => array(
                'email' => $user->getUsername()
            )
        ));
    }    
    
    
    public function testWithEmailAlreadyInUse() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $user = $this->makeUser();        
        $this->setUser($user);         
        $newEmail = 'user2@example.com';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'email-taken',
                'user_account_details_update_email' => $newEmail
            )
        ), array(
            'postData' => array(
                'email' => $newEmail
            )
        ));      
    }
    
    public function testWithUnknownErrorCommunicatingWithCoreApplication() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $user = $this->makeUser();        
        $this->setUser($user);        
        $newEmail = 'user2@example.com';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'unknown'
            )
        ), array(
            'postData' => array(
                'email' => $newEmail
            )
        ));
    }    
    
    public function testWithSuccessfulEmailChangeRequestCreation() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $user = $this->makeUser();        
        $this->setUser($user);        
        $newEmail = 'user2@example.com';
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'email-done'
            )
        ), array(
            'postData' => array(
                'email' => $newEmail
            )
        ));       
    }
    
    public function testWithCurrentPasswordAndNoNewPassword() {        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'password-missing'
            )
        ), array(
            'postData' => array(
                'current-password' => $user->getPassword()
            )
        ));        
    }
    
    public function testWithNoCurrentPasswordAndNewPassword() {        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'password-missing'
            )
        ), array(
            'postData' => array(
                'new-password' => $user->getPassword()
            )
        ));       
    }  
    
    public function testWithInvalidCurrentPassword() {        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'password-invalid'
            )
        ), array(
            'postData' => array(
                'current-password' => 'invalid-current-password',
                'new-password' => 'new-password'
            )
        ));        
    }   
    
    public function testWithCurrentPasswordAndNewPassword() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $user = $this->makeUser();        
        $this->setUser($user); 
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
            'flash' => array(
                'user_account_details_update_notice' => 'password-done'
            )
        ), array(
            'postData' => array(
                'current-password' => $user->getPassword(),
                'new-password' => 'new-password'
            )
        ));       
    }
   
}