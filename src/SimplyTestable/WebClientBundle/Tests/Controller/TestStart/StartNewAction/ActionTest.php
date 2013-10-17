<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestStart\StartNewAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestStart\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {      
    
    protected function getActionName() {
        return 'startNewAction';
    }  
    
    public function testStartActionWithNoWebsite() {          
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'website-blank'
            )
        ));
    }    
    
    public function testStartActionWithNoTestTypes() {          
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'no-test-types-selected'
            )
        ), array(
            'postData' => array(
                'website' => 'http://example.com'
            )
        ));        
    }     
    
    public function testStartActionWithWebsiteAndTestType() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/progress/'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));          
    }     
    
    public function testStartActionReceives503FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));         
    }    
    
    public function testStartActionReceives404FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }     
    
    public function testStartActionReceives500FromCoreApplication() {          
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//'
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }
    
    public function testStartRaisesCurlErrorCommunicatingWithCoreApplication() {       
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/',
            'flash' => array(
                'test_start_error' => 'curl-error',
                'curl_error_code' => 6
            )            
        ), array(
            'postData' => array(
                'website' => 'http://example.com',
                'html-validation' => '1'
            )
        ));
    }


}


