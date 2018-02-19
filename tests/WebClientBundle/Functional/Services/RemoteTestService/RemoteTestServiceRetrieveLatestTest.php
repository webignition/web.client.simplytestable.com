<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Tests\WebClientBundle\Factory\ConnectExceptionFactory;
use Tests\WebClientBundle\Factory\HttpResponseFactory;

class RemoteTestServiceRetrieveLatestTest extends AbstractRemoteTestServiceTest
{
    const WEBSITE_URL = 'http://example.com/';

    /**
     * @dataProvider retrieveLatestDataProvider
     *
     * @param array $httpFixtures
     * @param string|null $expectedRequestUrl
     * @param RemoteTest $expectedLatestTest
     */
    public function testRetrieveLatest(
        array $httpFixtures,
        $expectedRequestUrl,
        $expectedLatestTest
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $remoteTest = $this->remoteTestService->retrieveLatest(self::WEBSITE_URL);

        $this->assertEquals($expectedLatestTest, $remoteTest);

        if (!is_null($expectedRequestUrl)) {
            $this->assertEquals(
                $expectedRequestUrl,
                $this->getLastRequest()->getUrl()
            );
        }
    }

    /**
     * @return array
     */
    public function retrieveLatestDataProvider()
    {
        $remoteTest = new RemoteTest([
            'id' => 1,
        ]);

        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

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
