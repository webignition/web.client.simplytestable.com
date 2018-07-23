<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test\Test;
use App\Tests\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceLockUnlockTest extends AbstractRemoteTestServiceTest
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

        $this->setRemoteTestServiceTest($this->test);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testLock()
    {
        $this->remoteTestService->lock();
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-private/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock();
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-public/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
