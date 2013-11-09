<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task\IdCollectionAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Task\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }    
    
    protected function getActionName() {
        return 'idCollectionAction';
    }       
    
    public function testGetWithAuthorisedUser() {        
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
    
    public function testGetWithHttpClientErrorRetrievingRemoteIds() {        
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


