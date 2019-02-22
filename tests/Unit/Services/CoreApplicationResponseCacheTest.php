<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use App\Services\CoreApplicationResponseCache;
use App\Tests\Factory\HttpResponseFactory;

class CoreApplicationResponseCacheTest extends \PHPUnit\Framework\TestCase
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
     */
    public function testSet(RequestInterface $request, bool $expectedIsSet)
    {
        $cachedResponseTestHeaderKey = 'X-Test';
        $cachedResponseTestHeaderValue = 'Foo';
        $response = new Response(200, [$cachedResponseTestHeaderKey => $cachedResponseTestHeaderValue]);

        $this->assertNull($this->coreApplicationResponseCache->get($request));

        $this->coreApplicationResponseCache->set(
            $request,
            $response
        );

        $cachedResponse = $this->coreApplicationResponseCache->get($request);

        if ($expectedIsSet) {
            $this->assertEquals(
                $cachedResponseTestHeaderValue,
                $cachedResponse->getHeaderLine($cachedResponseTestHeaderKey)
            );
        } else {
            $this->assertNull($this->coreApplicationResponseCache->get($request));
        }
    }

    public function setDataProvider(): array
    {
        return [
            'GET request' => [
                'request' => new Request('GET', 'http://example.com'),
                'expectedIsSet' => true,
            ],
            'POST request' => [
                'request' => new Request('POST', 'http://example.com'),
                'expectedIsSet' => false,
            ],
        ];
    }

    /**
     * @dataProvider getSetNoCachedResponsesDataProvider
     */
    public function testGetSetNoCachedResponses(RequestInterface $request)
    {
        $cacheResponse = $this->coreApplicationResponseCache->get($request);

        $this->assertNull($cacheResponse);
    }

    public function getSetNoCachedResponsesDataProvider(): array
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
     */
    public function testGetSet(array $cacheContents, RequestInterface $request, ?string $expectedResponseContent)
    {
        foreach ($cacheContents as $httpTransaction) {
            $this->coreApplicationResponseCache->set($httpTransaction['request'], $httpTransaction['response']);
        }

        $cacheResponse = $this->coreApplicationResponseCache->get($request);

        if (empty($expectedResponseContent)) {
            $this->assertNull($cacheResponse);
        } else {
            $this->assertEquals($expectedResponseContent, (string)$cacheResponse->getBody());
        }
    }

    public function getSetDataProvider(): array
    {
        $getRequest1 = new Request('GET', 'http://example.com');
        $getRequest2 = new Request('GET', 'http://example.com/foo');
        $getRequest3 = new Request('GET', 'http://example.com/foo', [
            'foo' => 'bar',
        ]);

        $postRequest = new Request('POST', 'http://example.com');

        $response1Content = 'foo1';
        $response2Content = 'foo2';
        $response3Content = 'foo3';

        $response1 = HttpResponseFactory::createSuccessResponse([], $response1Content);
        $response2 = HttpResponseFactory::createSuccessResponse([], $response2Content);
        $response3 = HttpResponseFactory::createSuccessResponse([], $response3Content);

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
                'expectedResponseContent' => null,
            ],
            'GET request1' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest1,
                'expectedResponseContent' => $response1Content,
            ],
            'GET request2' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest2,
                'expectedResponseContent' => $response2Content,
            ],
            'GET request3' => [
                'cacheContents' => $cacheContents,
                'request' => $getRequest3,
                'expectedResponseContent' => $response3Content,
            ],
        ];
    }
}
