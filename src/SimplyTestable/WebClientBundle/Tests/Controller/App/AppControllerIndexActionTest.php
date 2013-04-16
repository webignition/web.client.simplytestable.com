<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testNormalMinimalUsage() {  
        $this->container->enterScope('request');     
        
        $appController = $this->getAppController('indexAction');
        $response = $appController->indexAction();
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
}


