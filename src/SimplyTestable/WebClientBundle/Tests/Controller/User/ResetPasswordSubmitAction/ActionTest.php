<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\ResetPasswordSubmitAction;

use SimplyTestable\WebClientBundle\Tests\Controller\User\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {     
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }     
    
    protected function getActionName() {
        return 'resetPasswordSubmitAction';
    }    

    
    public function testWithBlankEmail() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/',
            'flash' => array(
                'user_reset_password_error' => 'blank-email'
            )            
        ));              
    }
    
    public function testWithInvalidEmail() {        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/',
            'flash' => array(
                'user_reset_password_error' => 'invalid-email'
            )
        ), array(
            'postData' => array(
                'email' => 'foo'           
            )
        ));         
    }    

    public function testWithNonExistentUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/'
        ), array(
            'postData' => array(
                'email' => 'nonexistent-user@example.com'              
            )
        ));        
    }     

    
    public function testWithValidEmail() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/reset-password/',
            'flash' => array(
                'user_reset_password_confirmation' => 'token-sent'
            )               
        ), array(
            'postData' => array(
                'email' => 'user@example.com'              
            )
        ));   
    }

}


 