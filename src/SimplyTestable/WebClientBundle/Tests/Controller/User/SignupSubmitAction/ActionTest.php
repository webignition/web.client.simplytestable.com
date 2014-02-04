<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\SignupSubmitAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }     
    
    protected function getActionName() {
        return 'signUpSubmitAction';
    }  
    
    public function testWithBlankEmail() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'blank-email'
            )
        ), array(            
            'postData' => array(
                'email' => ''
            )
        ));
    }
    
    public function testWithInvalidEmail() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'invalid-email'
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
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'blank-password',
                'user_create_prefil' => 'user@example.com'
            )
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => ''
            )
        ));
    }    
    
    public function testWithPreExistingUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_confirmation' => 'user-exists'
            )
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => 'password'
            )
        ));        
    }

    public function testWithFailedDueToReadOnly() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'create-failed-read-only'
            )
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => 'password'
            )
        ));        
    }     

    
    public function testWithFailedDueToHttpServerError() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'create-failed'
            )
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => 'password'
            )
        ));      
    }     
    
    
    public function testWithFailedDueToHttpClientError() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/',
            'flash' => array(
                'user_create_error' => 'create-failed'
            )
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => 'password'
            )
        ));      
    }      
    
    
    public function testWithInvalidAdminCredentials() {
        try {
            $this->performActionTest(array(), array(            
                'postData' => array(
                    'email' => 'user@example.com',
                    'password' => 'password'
                )
            ));
            $this->fail('CoreApplicationAdminRequestException  401 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException $exception) {            
            $this->assertEquals(401, $exception->getCode());
            return;
        };       
    }    
    
    
    public function testSuccess() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/'
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => 'password'
            )
        ));       
    }
    
    
    public function testWithPasswordStartingWithAtCharacter() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signup/confirm/user@example.com/'
        ), array(            
            'postData' => array(
                'email' => 'user@example.com',
                'password' => '@password'
            )
        ));       
    }    
    

}


 