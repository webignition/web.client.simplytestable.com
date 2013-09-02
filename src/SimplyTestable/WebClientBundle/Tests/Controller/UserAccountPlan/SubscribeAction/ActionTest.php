<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan\SubscribeAction;

use SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/',
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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->setUser($this->makeUser());
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/account/'
        ), array(
            'postData' => array(
                'plan' => 'personal'
            )
        ));    
    }
    

   
}