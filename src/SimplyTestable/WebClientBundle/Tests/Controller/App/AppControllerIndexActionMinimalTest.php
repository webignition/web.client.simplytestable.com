<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerIndexActionMinimalTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testNormalMinimalUsage() {  
        $this->container->enterScope('request');
        $this->assertEquals(200, $this->getAppController('indexAction')->indexAction()->getStatusCode());
    }
}


