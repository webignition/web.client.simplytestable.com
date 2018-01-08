<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;

class RemoteTestServiceGetFinishedWebsitesTest extends AbstractRemoteTestServiceTest
{
    /**
     * @dataProvider getFinishedWebsitesDataProvider
     *
     * @param array $httpFixtures
     * @param array $expectedResponse
     * @param string $expectedRequestUrl
     */
    public function testGetFinishedWebsites(array $httpFixtures, $expectedResponse, $expectedRequestUrl)
    {
        $this->remoteTestService->setUser($this->user);

        $this->setHttpFixtures($httpFixtures);

        $response = $this->remoteTestService->getFinishedWebsites();

        $this->assertEquals($expectedResponse, $response);

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        if (is_null($expectedRequestUrl)) {
            $this->assertNull($lastRequest);
        } else {
            $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
        }
    }

    /**
     * @return array
     */
    public function getFinishedWebsitesDataProvider()
    {
        $websites = [
            'http://foo.example.com',
            'http://bar.example.com',
        ];

        $successfulRequestUrl = 'http://null/jobs/list/websites/?'
            . 'exclude-states%5B0%5D=cancelled&exclude-states%5B1%5D=rejected&exclude-current=1';

        return [
            'HTTP 500' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedResponse' => [],
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedResponse' => [],
                'expectedRequestUrl' => null,
            ],
            'success' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode($websites)),
                ],
                'expectedResponse' => $websites,
                'expectedRequestUrl' => $successfulRequestUrl,
            ],
        ];
    }
}
