<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Exception\CoreApplicationRequestException;
use App\Model\RemoteTest\RemoteTest;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceGetTest extends AbstractRemoteTestServiceTest
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

        $this->remoteTestService->get(1);
    }

    public function getRemoteFailureDataProvider(): array
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

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
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                    $curlTimeoutConnectException,
                ],
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

        $remoteTest = $this->remoteTestService->get($this->test->getTestId());

        $this->assertNull($remoteTest);
    }

    public function testGetSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $remoteTest = $this->remoteTestService->get($this->test->getTestId());

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
        $this->assertEquals('http://null/job/1/', $this->httpHistory->getLastRequestUrl());
    }
}
