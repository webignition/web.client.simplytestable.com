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
    const TEST_ID = 1;

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
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::TEST_ID,
            ]),
        ]);

        $remoteTest = $this->testRetriever->retrieve(self::TEST_ID);

        $this->assertInstanceOf(TestModel::class, $remoteTest);
        $this->assertEquals('http://null/job/' . self::TEST_ID . '/', $this->httpHistory->getLastRequestUrl());
    }

    /**
     * @dataProvider retrieveRemoteTaskIdsNotPreparedStateDataProvider
     */
    public function testRetrieveRemoteTaskIdsNotPreparedState(string $state)
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::TEST_ID,
                'state' => $state,
            ]),
        ]);

        $remoteTest = $this->testRetriever->retrieve(self::TEST_ID);

        $this->assertInstanceOf(TestModel::class, $remoteTest);
        $this->assertSame([], $remoteTest->getTaskIds());

        $this->assertEquals(
            [
                'http://null/job/1/'
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    public function retrieveRemoteTaskIdsNotPreparedStateDataProvider(): array
    {
        return [
            'new' => [
                'state' => 'new',
            ],
            'preparing' => [
                'state' => 'preparing',
            ],
        ];
    }

    /**
     * @dataProvider retrieveRemoteTaskIdsDataProvider
     */
    public function testRetrieveRemoteTaskIds(string $state)
    {
        $taskIds = [1,2,3];

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::TEST_ID,
                'state' => $state,
            ]),
            HttpResponseFactory::createJsonResponse($taskIds),
        ]);

        $remoteTest = $this->testRetriever->retrieve(self::TEST_ID);

        $this->assertInstanceOf(TestModel::class, $remoteTest);
        $this->assertSame($taskIds, $remoteTest->getTaskIds());

        $this->assertEquals(
            [
                'http://null/job/1/',
                'http://null/job/1/tasks/ids/',
            ],
            $this->httpHistory->getRequestUrlsAsStrings()
        );
    }

    public function retrieveRemoteTaskIdsDataProvider(): array
    {
        return [
            'queued' => [
                'state' => 'queued',
            ],
            'in-progress' => [
                'state' => 'in-progress',
            ],
            'completed' => [
                'state' => 'completed',
            ],
            'cancelled' => [
                'state' => 'cancelled',
            ],
            'expired' => [
                'state' => 'expired',
            ],
        ];
    }
}
