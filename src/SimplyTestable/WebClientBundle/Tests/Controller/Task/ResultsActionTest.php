<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Task;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class ResultsActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    


    public function testWithAuthorisedUserWithValidTestAndValidTask() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->container->enterScope('request');

        $response = $this->getTaskController('resultsAction')->resultsAction('http://blog.simplytestable.com/', 1, 1);
        
        $this->assertEquals(200, $response->getStatusCode());
    }  
    
    
    public function testWithAuthorisedUserWithValidTestAndInvalidTask() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        $this->container->enterScope('request');

        $response = $this->getTaskController('resultsAction')->resultsAction('http://example.com/', 1, 999);
        
        $this->assertEquals(302, $response->getStatusCode());
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/http://example.com//1/', $responseUrl->getPath());        
    }      
    
    public function testWithUnauthorisedUser() {
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));

        $response = $this->getTaskController('resultsAction')->resultsAction('http://techmites.com/', 14636, 8144097);
        
        $this->assertEquals(302, $response->getStatusCode());
        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/signin/', $responseUrl->getPath());
    } 
    


}


