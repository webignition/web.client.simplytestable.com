<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Test\CancelAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Test\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {           
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($this->getName())));
    }    

    protected function getActionName() {
        return 'cancelAction';
    }
    
    public function testCancelWithAuthorisedUser() {          
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/results/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'test_id' => 1
            )
        ));       
    }    
    
    public function testCancelWithUnauthorisedUser() {
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/'
        ));
    }
    
    
    public function testCancelWithHttpClientError() {
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


