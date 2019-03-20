<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceGetSummaryDataTest extends AbstractRemoteTestServiceTest
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
    }

    /**
     * @dataProvider getRemoteFailureDataProvider
     */
    public function testGetRemoteFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->getSummaryData(1);
    }

    public function getRemoteFailureDataProvider(): array
    {
        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'HTTP 500' => [
                'httpFixtures' => array_fill(0, 6, HttpResponseFactory::createInternalServerErrorResponse()),
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => array_fill(0, 6, ConnectExceptionFactory::create(28, 'Operation timed out')),
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testGetRemoteTestNotJsonDocument()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse([
                'content-type' => 'text/plain',
            ]),
        ]);

        $remoteTest = $this->remoteTestService->getSummaryData($this->test->getTestId());

        $this->assertNull($remoteTest);
    }

    public function testGetSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $remoteTestSummaryData = $this->remoteTestService->getSummaryData($this->test->getTestId());

        $this->assertIsArray($remoteTestSummaryData);
        $this->assertEquals('http://null/job/1/', $this->httpHistory->getLastRequestUrl());
    }
}
