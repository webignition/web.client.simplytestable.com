<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceLockUnlockTest extends AbstractRemoteTestServiceTest
{
    const TEST_ID = 1;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testLock()
    {
        $this->remoteTestService->lock(self::TEST_ID);
        $this->assertEquals(
            'http://null/job/1/set-private/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testUnlock()
    {
        $this->remoteTestService->unlock(self::TEST_ID);
        $this->assertEquals(
            'http://null/job/1/set-public/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
