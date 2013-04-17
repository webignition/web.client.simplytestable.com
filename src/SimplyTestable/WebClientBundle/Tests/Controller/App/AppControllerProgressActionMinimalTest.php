<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerProgressActionMinimalTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testGetProgressWithAuthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $this->assertEquals(200, $this->getAppController('progressAction')->progressAction('http://example.com/', 1)->getStatusCode());
    }
    
    public function testGetProgressWithUnauthorisedUser() {
        $this->removeAllTests();        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getAppController('progressAction')->progressAction('http://example.com/', 1);
        $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/signin/', $redirectUrl->getPath());
    }    
}


