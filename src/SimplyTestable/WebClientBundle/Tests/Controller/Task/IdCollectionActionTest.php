<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class IdCollectionActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testGetWithAuthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));

        $response = $this->getTaskController('idCollectionAction')->idCollectionAction('http://example.com/', 1);
    
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(array(1,2,3,4,5,6,7,8,9,10), json_decode($response->getContent()));
    }
    
    public function testGetWithUnauthorisedUser() {
        $this->removeAllTests();        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('idCollectionAction')->idCollectionAction('http://example.com/', 1);
        $this->assertEquals(404, $response->getStatusCode());

    } 
    
    public function testGetWithHttpClientErrorRetrievingRemoteIds() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('idCollectionAction')->idCollectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }    
    
    public function testGetWithHttpServerErrorRetrievingRemoteIds() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('idCollectionAction')->idCollectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }  
    
    public function testGetWithCurlExceptionRetrievingRemoteIds() {
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
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('idCollectionAction')->idCollectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }      
}


