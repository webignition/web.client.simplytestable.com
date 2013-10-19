<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\CollectionAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      

    protected function getActionName() {
        return 'collectionAction';
    }    
    
    
    public function testGetWithAuthorisedUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response =  $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));        

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
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 404
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));       
    } 
    
    
    public function testWithPublicTestAccessedByNonOwner() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response =  $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }    
    

    public function testGetWithHttpClientErrorRetrievingRemoteTasks() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));

        $this->assertNull(json_decode($response->getContent()));        
    }
    
    
    public function testGetWithHttpServerErrorRetrievingRemoteTasks() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));

        $this->assertNull(json_decode($response->getContent()));          
    }      
    
    public function testGetWithCurlExceptionRetrievingRemoteTasks() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));   
        
        $response = $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));

        $this->assertNull(json_decode($response->getContent()));        
    }
    
    
    public function testJsStaticAnalysisResultParsingOfEmptyCollection() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));      
    }

}


