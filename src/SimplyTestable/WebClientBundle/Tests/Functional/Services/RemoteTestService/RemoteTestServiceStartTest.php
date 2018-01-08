<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\TestOptions;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;
use webignition\WebResource\JsonDocument\JsonDocument;

class RemoteTestServiceStartTest extends AbstractRemoteTestServiceTest
{
    const CANONICAL_URL = 'http://example.com';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->remoteTestService->setUser($this->user);
    }

    public function testStartCurlException()
    {
        $this->setHttpFixtures([
            CurlExceptionFactory::create('Operation timed out', 28),
        ]);

        $this->setExpectedException(CurlException::class);
        $this->remoteTestService->start(
            self::CANONICAL_URL,
            ModelFactory::createTestOptions()
        );
    }

    /**
     * @dataProvider startSuccessDataProvider
     *
     * @param TestOptions $testOptions
     *
     * @throws WebResourceException
     */
    public function testStartSuccess(TestOptions $testOptions, $expectedRequestUrl)
    {
        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                'id' => 1,
            ]))
        ]);

        $response = $this->remoteTestService->start(
            self::CANONICAL_URL,
            $testOptions
        );

        $this->assertInstanceOf(JsonDocument::class, $response);

        $lastRequest = $this->httpHistoryPlugin->getLastRequest();

        $this->assertEquals($expectedRequestUrl, $lastRequest->getUrl());
    }

    /**
     * @return array
     */
    public function startSuccessDataProvider()
    {
        return [
            'foo' => [
                'testOptions' => ModelFactory::createTestOptions(),
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/?type=full%20site',
            ],
            'custom cookies' => [
                'testOptions' => ModelFactory::createTestOptions([
                    ModelFactory::TEST_OPTIONS_FEATURE_OPTIONS => [
                        'cookies' => [
                            'cookies' => [
                                [
                                    'name' => 'cookie-name',
                                    'value' => 'cookie-value',
                                ],
                            ],
                        ],
                    ],
                ]),
                'expectedRequestUrl' => 'http://null/job/http%3A%2F%2Fexample.com/start/?' . http_build_query([
                    'type' => 'full site',
                    'parameters' => [
                        'cookies' => [
                            [
                                'name' => 'cookie-name',
                                'value' => 'cookie-value',
                                'path' => '/',
                                'domain' => '.example.com',
                            ],
                        ],
                    ],
                ], null, '&', PHP_QUERY_RFC3986),
            ],
        ];
    }
}
