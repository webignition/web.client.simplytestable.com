<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\IdCollectionAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      
    
    protected function getActionName() {
        return 'idCollectionAction';
    }       
    
    public function testGetWithAuthorisedUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $response = $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1
            )
        ));          

        $this->assertEquals(array(1,2,3,4,5,6,7,8,9,10), json_decode($response->getContent()));
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
    
    public function testGetWithHttpClientErrorRetrievingRemoteIds() {
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
    
    public function testGetWithHttpServerErrorRetrievingRemoteIds() {
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
    
    public function testGetWithCurlExceptionRetrievingRemoteIds() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http%3A%2F%2Fexample.com%2F/1/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));        
        
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
}


