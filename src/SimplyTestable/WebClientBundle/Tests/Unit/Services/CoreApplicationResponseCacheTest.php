<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services;

use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Services\CoreApplicationResponseCache;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;

class CoreApplicationResponseCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CoreApplicationResponseCache
     */
    private $coreApplicationResponseCache;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->coreApplicationResponseCache = new CoreApplicationResponseCache();
    }

    /**
     * @dataProvider setDataProvider
     *
     * @param RequestInterface $request
     * @param bool $expectedReturnValue
     */
    public function testSet(RequestInterface $request, $expectedReturnValue)
    {
        $setReturnValue = $this->coreApplicationResponseCache->set(
            $request,
            HttpResponseFactory::createSuccessResponse()
        );

        $this->assertEquals($expectedReturnValue, $setReturnValue);
    }

    /**
     * @return array
     */
    public function setDataProvider()
    {
        return [
            'GET request' => [
                'request' => new Request('GET', 'http://example.com'),
                'expectedReturnValue' => true,
            ],
            'POST request' => [
                'request' => new Request('POST', 'http://example.com'),
                'expectedReturnValue' => false,
            ],
        ];
    }

    /**
     * @dataProvider getSetNoCachedResponsesDataProvider
     *
     * @param RequestInterface $request
     */
    public function testGetSetNoCachedResponses(RequestInterface $request)
    {
        $cacheResponse = $this->coreApplicationResponseCache->get($request);

        $this->assertNull($cacheResponse);
    }

    /**
     * @return array
     */
    public function getSetNoCachedResponsesDataProvider()
    {
        return [
            'GET request' => [
                'request' => new Request('GET', 'http://example.com'),
            ],
            'POST request' => [
                'request' => new Request('POST', 'http://example.com'),
            ],
        ];
    }

    /**
     * @dataProvider getSetDataProvider
     *
     * @param array $cacheContents
     * @param RequestInterface $request
     *
     * @param Response|null $expectedResponse
     */
    public function testGetSet(array $cacheContents, RequestInterface $request, $expectedResponse)
    {
        foreach ($cacheContents as $httpTransaction) {
            $this->coreApplicationResponseCache->set($httpTransaction['request'], $httpTransaction['response']);
        }

        $cacheResponse = $this->coreApplicationResponseCache->get($request);

        $this->assertEquals($expectedResponse, $cacheResponse);
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $getRequest1 = new Request('GET', 'http://example.com');
        $getRequest2 = new Request('GET', 'http://example.com/foo');
        $getRequest3 = new Request('GET', 'http://example.com/foo', [
            'foo' => 'bar',
        ]);

        $postRequest = new Request('POST', 'http://example.com');

        $response1 = HttpResponseFactory::createSuccessResponse();
        $response2 = HttpResponseFactory::createSuccessResponse();
        $response3 = HttpResponseFactory::createSuccessResponse();

        $cacheContents = [
            [
                'request' => $getRequest1,
                'response' => $response1,
            ],
            [
                'request' => $getRequest2,
                'response' => $response2,
            ],
            [
                'request' => $getRequest3,
                'response' => $response3,
            ],
        ];

        return [
            'POST request' => [
                'cacheContents' => $cacheContents,
                'request' => $postRequest,
                'expectedResponse' => null,
            ],
            'GET request1' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest1,
                'expectedResponse' => $response1,
            ],
            'GET request2' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest2,
                'expectedResponse' => $response2,
            ],
            'GET request3' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest3,
                'expectedResponse' => $response3,
            ],
        ];
    }
}
