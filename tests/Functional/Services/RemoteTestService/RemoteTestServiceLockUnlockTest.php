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

        $this->test = Test::create(1);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testLock()
    {
        $this->remoteTestService->lock($this->test->getTestId());
        $this->assertEquals(
            'http://null/job/1/set-private/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock($this->test->getTestId());
        $this->assertEquals(
            'http://null/job/1/set-public/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
