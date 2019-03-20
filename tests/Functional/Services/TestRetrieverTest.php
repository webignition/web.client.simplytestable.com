<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Model\Test as TestModel;
use App\Exception\CoreApplicationRequestException;
use App\Services\TestRetriever;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class TestRetrieverTest extends AbstractCoreApplicationServiceTest
{
    /**
     * @var TestRetriever
     */
    private $testRetriever;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testRetriever = self::$container->get(TestRetriever::class);
    }

    /**
     * @dataProvider retrieveRemoteFailureDataProvider
     */
    public function testRetrieveRemoteFailure(
        array $httpFixtures,
        string $expectedException,
        string $expectedExceptionMessage,
        int $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->testRetriever->retrieve(1);
    }

    public function retrieveRemoteFailureDataProvider(): array
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

    public function testRetrieveRemoteTestNotJsonDocument()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse([
                'content-type' => 'text/plain',
            ]),
        ]);

        $remoteTest = $this->testRetriever->retrieve(1);

        $this->assertNull($remoteTest);
    }

    public function testRetrieveSuccess()
    {
        $testId = 1;

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => $testId,
            ]),
        ]);

        $remoteTest = $this->testRetriever->retrieve($testId);

        $this->assertInstanceOf(TestModel::class, $remoteTest);
        $this->assertEquals('http://null/job/1/', $this->httpHistory->getLastRequestUrl());
    }
}
