<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\UserAccountPlan;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class SubscribeActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }
    
    public function testWithoutBeingLoggedIn() {
        $response = $this->getUserAccountPlanController('subscribeAction')->subscribeAction();
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());          
    } 
    
    
    public function testWhenCoreApplicationHasInvalidStripeApiKey() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->setUser($this->makeUser());
       
        $this->getUserAccountPlanController('subscribeAction', array(
            'plan' => 'personal'
        ))->subscribeAction();
        
        $this->assertEquals(403, $this->container->get('session')->getFlash('plan_subscribe_error'));     
    }    
    
    
    public function testBasicToPersonal() {        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->setUser($this->makeUser());
       
        $response = $this->getUserAccountPlanController('subscribeAction', array(
            'plan' => 'personal'
        ))->subscribeAction();
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/account/', $responseUrl->getPath());          
    }
    

   
}