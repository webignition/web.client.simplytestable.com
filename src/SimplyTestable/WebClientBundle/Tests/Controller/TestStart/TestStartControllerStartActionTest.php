<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestStart;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class TestStartControllerStartActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testStartActionWithNoWebsite() {          
        $response = $this->getTestStartController('startAction')->startAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('website-blank', $this->container->get('session')->getFlash('test_start_error'));
    }    
    
    public function testStartActionWithNoTestTypes() {          
        $response = $this->getTestStartController('startAction', array(
            'website' => 'http://example.com'
        ))->startAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('no-test-types-selected', $this->container->get('session')->getFlash('test_start_error'));
    }     
    
    public function testStartActionWithWebsiteAndTestType() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestStartController('startAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRegExp('/http:\/\/localhost\/http:\/\/example.com\/\/[0-9]?\/progress\//', $response->getTargetUrl());
    }     
}


