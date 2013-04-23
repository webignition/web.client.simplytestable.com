<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;
use SimplyTestable\WebClientBundle\Model\User;

class AppControllerIndexActionMinimalTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testNormalMinimalUsage() {  
        $this->container->enterScope('request');
        $this->assertEquals(200, $this->getAppController('indexAction')->indexAction()->getStatusCode());
    }
    
    public function testLoggedInUserRecentTests() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));        
        $this->setUser(new User('user@example.com','password'));
        
        $this->container->enterScope('request');       
        $this->assertEquals(200, $this->getAppController('indexAction')->indexAction()->getStatusCode());       
    }
}


