<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestStart;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class TestStartControllerStartActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testStartActionWithNoWebsite() {          
        $response = $this->getTestStartController('startNewAction')->startNewAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('website-blank', $this->container->get('session')->getFlash('test_start_error'));
    }    
    
    public function testStartActionWithNoTestTypes() {          
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com'
        ))->startNewAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('no-test-types-selected', $this->container->get('session')->getFlash('test_start_error'));
    }     
    
    public function testStartActionWithWebsiteAndTestType() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startNewAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRegExp('/http:\/\/localhost\/http:\/\/example.com\/\/[0-9]?\/progress\//', $response->getTargetUrl());
    }     
    
    public function testStartActionReceives503FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startNewAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//', $response->getTargetUrl());
    }    
    
    public function testStartActionReceives404FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startNewAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//', $response->getTargetUrl());
    }     
    
    public function testStartActionReceives500FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startNewAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//', $response->getTargetUrl());
    }
    
    public function testStartRaisesCurlErrorCommunicatingWithCoreApplication() {       
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http://example.com//start/?type=full%20site&test-types%5B0%5D=HTML%20validation' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));        
        
        $response = $this->getTestStartController('startNewAction', array(
            'website' => 'http://example.com',
            'html-validation' => '1'
        ))->startNewAction();
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('curl-error', $this->container->get('session')->getFlash('test_start_error'));
        $this->assertEquals(6, $this->container->get('session')->getFlash('curl_error_code'));
    }
}


