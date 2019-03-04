<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Model\RemoteTest\RemoteTest;
use App\Tests\Factory\ConnectExceptionFactory;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceRetrieveLatestTest extends AbstractRemoteTestServiceTest
{
    const WEBSITE_URL = 'http://example.com/';

    /**
     * @dataProvider retrieveLatestDataProvider
     */
    public function testRetrieveLatest(
        array $httpFixtures,
        ?string $expectedRequestUrl,
        ?RemoteTest $expectedLatestTest
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $remoteTest = $this->remoteTestService->retrieveLatest(self::WEBSITE_URL);

        $this->assertEquals($expectedLatestTest, $remoteTest);

        if (!is_null($expectedRequestUrl)) {
            $this->assertEquals(
                $expectedRequestUrl,
                $this->httpHistory->getLastRequestUrl()
            );
        }
    }

    public function retrieveLatestDataProvider(): array
    {
        $remoteTest = new RemoteTest([
            'id' => 1,
        ]);

        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => null,
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
                'expectedRequestUrl' => null,
                'expectedLatestTest' => null,
            ],
            'Invalid response content' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => null,
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => $remoteTest,
            ],
        ];
    }
}
