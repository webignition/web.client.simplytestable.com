<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceHasTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->test = new Test();
        $this->test->setTestId(1);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));
    }

    public function testHasNoRemoteTest()
    {
        $this->assertFalse($this->remoteTestService->has());
    }

    public function testHasNoLocalTest()
    {
        $remoteTest = new RemoteTest(new \stdClass());
        $this->remoteTestService->set($remoteTest);

        $this->assertFalse($this->remoteTestService->has());
    }

    public function testHas()
    {
        $remoteTestId = 2;

        $remoteTestData = new \stdClass();
        $remoteTestData->id = $remoteTestId;

        $this->test->setTestId($remoteTestId);
        $this->setRemoteTestServiceTest($this->test);

        $remoteTest = new RemoteTest($remoteTestData);
        $this->remoteTestService->set($remoteTest);

        $this->assertTrue($this->remoteTestService->has());
    }
}
