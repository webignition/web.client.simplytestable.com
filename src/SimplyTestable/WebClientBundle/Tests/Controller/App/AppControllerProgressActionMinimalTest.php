<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerProgressActionMinimalTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testGetProgressWithAuthorisedUser() {
        $this->performProgressActionTest(array(
            'statusCode' => 200
        ));
    }
    
    public function testGetProgressWithUnauthorisedUser() {
        $this->performProgressActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));
    } 
    
    
    public function testGetProgressWithNonExistentTest() {
        $this->performProgressActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/signin/'
        ));
    }   
    
    
    public function testGetProgressWithFinishedTest() {
        $this->performProgressActionTest(array(
            'statusCode' => 302,
            'redirectPath' => '/http://example.com//1/results/'
        ));
    } 
    
    
    public function testGetProgressWithAuthorisedUserAsJson() {
        $this->performProgressActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(),
            'queryData' => array(
                'output' => 'json'
            )
        ));
    }   
    
    
    private function performProgressActionTest($responseProperties, $methodProperties = array()) {
        list(, $caller) = debug_backtrace(false);
        
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath($caller['function'] . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $postData = isset($methodProperties['postData']) ? $methodProperties['postData'] : array();
        $queryData = isset($methodProperties['queryData']) ? $methodProperties['queryData'] : array();
        
        $response = $this->getAppController(
            'progressAction',
            $postData,
            $queryData
        )->progressAction('http://example.com/', 1);
        
        $this->assertEquals($responseProperties['statusCode'], $response->getStatusCode());        
        
        if ($response->getStatusCode() == 302) {
            $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
            $this->assertEquals($responseProperties['redirectPath'], $redirectUrl->getPath());            
        }
    }
    
    
    public function testGetProgressWithHttpClientErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        try {
            $this->getAppController('progressAction')->progressAction('http://example.com/', 1);
            $this->fail('WebResourceException 404 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(400, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }    
    
    public function testGetProgressWithHttpServerErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        try {
            $this->getAppController('progressAction')->progressAction('http://example.com/', 1);
            $this->fail('WebResourceException 500 has not been raised.');
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceException $webResourceException) {
            $this->assertEquals(500, $webResourceException->getResponse()->getStatusCode());
            return;
        };
    }
    
    
    public function testGetProgressWithCurlErrorRetrievingRemoteSummary() {
        $this->removeAllTests();
        $this->getWebResourceService()->setRequestSkeletonToCurlErrorMap(array(
            'http://ci.app.simplytestable.com/job/http://example.com//1/' => array(
                'GET' => array(
                    'errorMessage' => "Couldn't resolve host. The given remote host was not resolved.",
                    'errorNumber' => 6                    
                )
            )
        ));
        
        $this->container->enterScope('request');
        
        try {
            $this->getAppController('progressAction')->progressAction('http://example.com/', 1);
            $this->fail('CurlException 6 has not been raised.');
        } catch (\Guzzle\Http\Exception\CurlException $curlException) {
            $this->assertEquals(6, $curlException->getErrorNo());
            return;
        };
    }     
    

   
}


