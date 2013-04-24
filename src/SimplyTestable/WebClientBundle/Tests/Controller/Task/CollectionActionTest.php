<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class CollectionActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testGetWithAuthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));

        $response = $this->getTaskController('collectionAction')->CollectionAction('http://example.com/', 1);
    
        $this->assertEquals(200, $response->getStatusCode());
        $taskDetails = json_decode($response->getContent());
        
        $processedTaskIds = array();
        
        foreach ($taskDetails as $taskDetailIndex => $taskDetail) {            
            $this->assertInternalType('integer', $taskDetail->id);
            $this->assertFalse(in_array($taskDetail->id, $processedTaskIds));
            $this->assertEquals($taskDetailIndex, $taskDetail->task_id);
            $this->assertInternalType('string', $taskDetail->url);
            $this->assertInternalType('string', $taskDetail->state);
            $this->assertInternalType('string', $taskDetail->worker);
            $this->assertInternalType('string', $taskDetail->type);
            $this->assertInternalType('string', $taskDetail->type);
            
            $processedTaskIds[] = $taskDetail->id;
        }
    }
    
    public function testGetWithUnauthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));

        $response = $this->getTaskController('collectionAction')->CollectionAction('http://example.com/', 1);
        $this->assertEquals(404, $response->getStatusCode());
    } 
    

    public function testGetWithHttpClientErrorRetrievingRemoteTasks() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('collectionAction')->collectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }
    
    
    public function testGetWithHttpServerErrorRetrievingRemoteTasks() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('collectionAction')->collectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }      
    
    public function testGetWithCurlExceptionRetrievingRemoteTasks() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http://example.com//1/tasks/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));        
        
        $this->container->enterScope('request');
        
        $response = $this->getTaskController('collectionAction')->collectionAction('http://example.com/', 1);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertNull(json_decode($response->getContent()));        
    }
    
    
    public function testJsStaticAnalysisResultParsingOfEmptyCollection() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));

        $response = $this->getTaskController('collectionAction')->CollectionAction('http://lautokaurbana.com/', 4857);
    
        $this->assertEquals(200, $response->getStatusCode());        
    }
}


