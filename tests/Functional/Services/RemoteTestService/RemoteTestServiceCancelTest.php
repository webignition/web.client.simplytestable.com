<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceCancelTest extends AbstractRemoteTestServiceTest
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

    public function testCancel()
    {
        $this->remoteTestService->cancel(self::TEST_ID);

        $this->assertEquals(
            'http://null/job/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testCancelByTestProperties()
    {
        $this->remoteTestService->cancelByTestProperties(self::TEST_ID);

        $this->assertEquals(
            'http://null/job/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
