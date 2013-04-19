<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\App;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class AppControllerProgressActionMinimalTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    public function testGetProgressWithAuthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $this->assertEquals(200, $this->getAppController('progressAction')->progressAction('http://example.com/', 1)->getStatusCode());
    }
    
    public function testGetProgressWithUnauthorisedUser() {
        $this->removeAllTests();        
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getAppController('progressAction')->progressAction('http://example.com/', 1);
        $redirectUrl = new \webignition\Url\Url($response->getTargetUrl());
        
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals('/signin/', $redirectUrl->getPath());
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
    
    public function testGetProgressWithAuthorisedUserAsJson() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->container->enterScope('request');
        
        $response = $this->getAppController(
            'progressAction',
            array(),
            array(
                'output' => 'json'
            )
        )->progressAction('http://example.com/', 1);
        
        $this->assertEquals(200, $response->getStatusCode());
    }    
}


