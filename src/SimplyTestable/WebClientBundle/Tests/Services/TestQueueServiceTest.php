<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TestOptions;

use SimplyTestable\WebClientBundle\Tests\BaseSimplyTestableTestCase;

class TestQueueServiceTest extends BaseSimplyTestableTestCase {   
    
    const TEST_CANONICAL_URL = 'http://example.com/';
    const TEST_TYPE = 'full site';
    const TEST_REASON = 503;
    
    public function testClear() {
        $this->getTestQueueService()->clear();
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');        
        $this->assertTrue($this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            self::TEST_CANONICAL_URL,
            $testOptions,
            self::TEST_TYPE,
            self::TEST_REASON
        ));
        
        $this->assertEquals(1, $this->getTestQueueService()->count());
        $this->getTestQueueService()->clear();
        $this->assertEquals(0, $this->getTestQueueService()->count());
    }

    public function testEnqueue() {        
        $this->getTestQueueService()->clear();
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->assertTrue($this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            self::TEST_CANONICAL_URL,
            $testOptions,
            self::TEST_TYPE,
            self::TEST_REASON
        ));
    }    
    
    public function testDequeue() { 
        $this->getTestQueueService()->clear();
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            self::TEST_CANONICAL_URL,
            $testOptions,
            self::TEST_TYPE,
            self::TEST_REASON
        );
        
        $queuedTest = $this->getTestQueueService()->dequeue($this->getUserService()->getPublicUser(), self::TEST_CANONICAL_URL);
        
        $this->assertEquals($this->getUserService()->getPublicUser(), $queuedTest['user']);
        $this->assertEquals($testOptions, $queuedTest['options']);
        $this->assertEquals(self::TEST_TYPE, $queuedTest['type']);
        $this->assertEquals(self::TEST_CANONICAL_URL, $queuedTest['url']);
        $this->assertEquals(self::TEST_REASON, $queuedTest['reason']);        
    }
    
    
    public function testContains() {
        $this->getTestQueueService()->clear();
        
        $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
        $testOptions->addTestType('HTML validation');
        
        $this->getTestQueueService()->enqueue(
            $this->getUserService()->getPublicUser(),
            self::TEST_CANONICAL_URL,
            $testOptions,
            self::TEST_TYPE,
            self::TEST_REASON
        );
        
        $this->assertTrue($this->getTestQueueService()->contains($this->getUserService()->getPublicUser(), self::TEST_CANONICAL_URL));        
    } 

}
