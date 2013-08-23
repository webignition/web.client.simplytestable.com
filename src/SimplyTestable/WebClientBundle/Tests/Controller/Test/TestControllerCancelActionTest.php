<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Test;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class TestControllerCancelActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testCancelWithAuthorisedUser() {          
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestController('cancelAction', array(
            'website' => 'http://example.com',
            'test_id' => 1
        ))->cancelAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//1/results/', $response->getTargetUrl());
    }    
    
    public function testCancelWithUnauthorisedUser() {          
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestController('cancelAction', array(
            'website' => 'http://example.com',
            'test_id' => 1
        ))->cancelAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/', $response->getTargetUrl());
    }
    
    
    public function testCancelWithHttpClientError() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestController('cancelAction', array(
            'website' => 'http://example.com',
            'test_id' => 1
        ))->cancelAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//1/progress/', $response->getTargetUrl());       
    }
    
    
    public function testCancelWithHttpServerError() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->getTestController('cancelAction', array(
            'website' => 'http://example.com',
            'test_id' => 1
        ))->cancelAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//1/progress/', $response->getTargetUrl());        
    }      
    
    public function testCancelWithCurlException() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http%3A%2F%2Fexample.com%2F/1/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));
        
        $response = $this->getTestController('cancelAction', array(
            'website' => 'http://example.com',
            'test_id' => 1
        ))->cancelAction();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('http://localhost/http://example.com//1/progress/', $response->getTargetUrl());    
    }     
    

}


