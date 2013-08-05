<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class TestActionTest extends BaseSimplyTestableTestCase {    
    
    public static function setUpBeforeClass() {
        self::setupDatabaseIfNotExists();
    }    
    
    
    public function testWithWebSiteOnlyThatIsQueued() {
        $this->getTestQueueService()->clear();
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');        
        $this->assertTrue($this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            'http://example.com/',
            $testOptions,
            'full site',
            503
        ));        
        
        $response = $this->getRedirectController('testAction', array(
            'website' => 'http://example.com/'
        ))->testAction('http://example.com/');
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/http://example.com//queued/', $responseUrl->getPath());          
    }
    
    public function testWithWebSiteOnly() { 
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath(__FUNCTION__ . '/HttpResponses')));
        
        $this->getTestQueueService()->clear();
        
        $response = $this->getRedirectController('testAction', array(
            'website' => 'http://example.com/'
        ))->testAction('http://example.com/');
        
        $this->assertEquals(302, $response->getStatusCode());        
        $responseUrl = new \webignition\Url\Url($response->getTargetUrl());
        $this->assertEquals('/', $responseUrl->getPath());          
    }    

}


