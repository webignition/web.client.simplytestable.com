<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Test\CancelAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Test\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {           

    protected function getActionName() {
        return 'cancelAction';
    }
    
    public function testCancelWithAuthorisedUser() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/results/'
        ));        
    }    
    
    public function testCancelWithUnauthorisedUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/'
        ));
    }
    
    
    public function testCancelWithHttpClientError() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'test_id' => 1
            )
        ));
    }
    
    
    public function testCancelWithHttpServerError() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'test_id' => 1
            )
        ));
    }      
    
    public function testCancelWithCurlException() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
            
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'test_id' => 1
            )
        ));   
    }
     
    

}


