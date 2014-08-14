<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan\SubscribeAction;

use SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    public function setUp() {
        parent::setUp();
        
        if ($this->hasCustomFixturesDataPath($this->getName())) {
            $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
        }
    }     
    
    protected function getActionName() {
        return 'subscribeAction';
    } 
    
    public function testWithoutBeingLoggedIn() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));       
    } 
    
    
    public function testWhenCoreApplicationHasInvalidStripeApiKey() {        
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/plan/',
            'flash' => array(
                'plan_subscribe_error' => 403
            )
        ), array(
            'postData' => array(
                'plan' => 'personal'
            )
        ));  
    }    
    
    
    public function testBasicToPersonal() {        
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/plan/'
        ), array(
            'postData' => array(
                'plan' => 'personal'
            )
        ));    
    }
    

   
}