<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Task\CollectionAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Task\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }

    protected function getActionName() {
        return 'collectionAction';
    }    
    
    
    public function testGetWithAuthorisedUser() {
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
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));
    }    
    

    public function testGetWithHttpClientErrorRetrievingRemoteTasks() {
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


