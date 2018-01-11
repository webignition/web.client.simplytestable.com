<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;

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
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

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
        $remoteTestData = new \stdClass();
        $remoteTestData->id = 1;

        $remoteTest = new RemoteTest($remoteTestData);

        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => null,
            ],

            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedRequestUrl' => null,
                'expectedLatestTest' => null,
            ],
            'Invalid response content' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:text/plain\n\n"),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => null,
            ],
            'Success' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'id' => 1
                    ])),
                ],
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com%2F/latest/',
                'expectedLatestTest' => $remoteTest,
            ],
        ];
    }
}
