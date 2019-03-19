<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Model\TestIdentifier;
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
        ?TestIdentifier $expectedTestIdentifier
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $testIdentifier = $this->remoteTestService->retrieveLatest(self::WEBSITE_URL);

        $this->assertEquals($expectedTestIdentifier, $testIdentifier);

        if (!is_null($expectedRequestUrl)) {
            $this->assertEquals(
                $expectedRequestUrl,
                $this->httpHistory->getLastRequestUrl()
            );
        }
    }

    public function retrieveLatestDataProvider(): array
    {
        $curlTimeoutConnectException = ConnectExceptionFactory::create(28, 'Operation timed out');

        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => null,
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
                'expectedTestIdentifier' => null,
            ],
            'Invalid response content' => [
                'httpFixtures' => [
                    HttpResponseFactory::createSuccessResponse([
                        'content-type' => 'text/plain',
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => null,
            ],
            'Success' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                    ]),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedTestIdentifier' => new TestIdentifier(1, 'http://example.com/'),
            ],
        ];
    }
}
