<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test\Test;
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

        $this->setRemoteTestServiceTest($this->test);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse(),
        ]);
    }

    public function testCancel()
    {
        $this->remoteTestService->cancel();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }

    public function testCancelByTestProperties()
    {
        $this->remoteTestService->cancelByTestProperties(2, 'http://foo.example.com');

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Ffoo.example.com/2/cancel/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
