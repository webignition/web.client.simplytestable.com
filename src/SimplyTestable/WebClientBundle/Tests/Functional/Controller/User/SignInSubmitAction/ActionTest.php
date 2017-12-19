<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\User\SignInSubmitAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }       

    protected function getActionName() {
        return 'signInSubmitAction';
    }
    
    public function testWithBlankEmail() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'blank-email'
            )
        ));
    }    
    
    public function testWithInvalidEmail() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'invalid-email'
            )
        ), array(
            'postData' => array(
                'email' => 'foobar'               
            )
        ));
    }     
    
    public function testWithBlankPassword() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'blank-password'
            )
        ), array(
            'postData' => array(
                'email' => 'user@example.com'              
            )
        ));
    }     
    
    
    public function testWithPublicUser() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'public-user'
            )
        ), array(
            'postData' => array(
                'email' => 'public@simplytestable.com',
                'password' => 'password'
            )
        ));
    } 
    
    
    public function testWithInvalidUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'invalid-user'
            )
        ), array(
            'postData' => array(
                'email' => 'invalid-user@example.com',
                'password' => 'password'
            )
        ));       
    }   
    
    
    public function testWithInvalidCredentials() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'authentication-failure'
            )
        ), array(
            'postData' => array(
                'email' => 'invalid-user@example.com',
                'password' => 'password'
            )
        ));        
    }    
    
    
    public function testWithInvalidUserThatIsNotEnabled() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'user-not-enabled'
            )
        ), array(
            'postData' => array(
                'email' => 'invalid-user@example.com',
                'password' => 'password'
            )
        ));
    }    
    
    public function testWithValidUserThatIsNotEnabled() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/',
            'flash' => array(
                'user_signin_error' => 'user-not-enabled'
            )
        ), array(
            'postData' => array(
                'email' => 'valid-user@example.com',
                'password' => 'password'
            )
        ));
    } 
    
    
    public function testWithValidUserThatIsEnabled() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/'
        ), array(
            'postData' => array(
                'email' => 'valid-user@example.com',
                'password' => 'password'
            )
        ));
    }


}


 