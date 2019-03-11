<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceCancelTest extends AbstractRemoteTestServiceTest
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

    public function testCancel()
    {
        $this->remoteTestService->cancel($this->test);

        $this->assertEquals(
            'http://null/job/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testCancelByTestProperties()
    {
        $this->remoteTestService->cancelByTestProperties(2);

        $this->assertEquals(
            'http://null/job/2/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
