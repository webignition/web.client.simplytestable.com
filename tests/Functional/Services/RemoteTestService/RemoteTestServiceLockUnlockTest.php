<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Tests\Factory\HttpResponseFactory;

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

        $this->test = Test::create(1, 'http://example.com/');

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testLock()
    {
        $this->remoteTestService->lock($this->test);
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-private/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock($this->test);
        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/set-public/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
