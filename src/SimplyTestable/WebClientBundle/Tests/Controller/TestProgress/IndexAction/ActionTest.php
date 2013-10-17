<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\TestProgress\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {    
    
    protected function getActionName() {
        return 'indexAction';
    }
    
    public function testWithAuthorisedUser() {
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
    
    public function testWithUnauthorisedUser() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1                
            )
        ));
    } 
    
    
    public function testWithNonExistentTest() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1                
            )
        ));
    }   
    
    
    public function testWithFinishedTest() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->performActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/results/'
        ), array(
            'methodArguments' => array(
                'http://example.com/',
                1                
            )
        ));
    } 
    
    
    public function testWithAuthorisedUserAsJson() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(),
            'queryData' => array(
                'output' => 'json'
            ),
            'methodArguments' => array(
                'http://example.com/',
                1                
            )            
        ));
    }
    
    
    public function testWithHttpClientErrorRetrievingRemoteSummary() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1                
                )
            ));
            $this->fail('WebResourceException 400 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(400, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }    
    
    public function testWithHttpServerErrorRetrievingRemoteSummary() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1                
                )
            ));
            $this->fail('WebResourceException 500 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(500, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }
    
    
    public function testWithCurlErrorRetrievingRemoteSummary() {
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        try {
            $this->performActionTest(array(), array(
                'methodArguments' => array(
                    'http://example.com/',
                    1                
                )
            ));
            $this->fail('CurlException 6 has not been raised.');
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->assertEquals(6, $curlException->getErrorNo());
            return;
        };
    }  
}