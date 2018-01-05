<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services;

use SimplyTestable\WebClientBundle\Services\CacheableResponseService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheableResponseServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheableResponseService
     */
    private $cacheableResponseService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cacheableResponseService = new CacheableResponseService();
    }

    /**
     * @dataProvider getCacheableResponseDataProvider
     *
     * @param Request $request
     * @param Response|null $response
     * @param string $expectedCacheControlHeader
     * @param string $expectedEtag
     * @param string $expectedLastModified
     */
    public function testGetCacheableResponse(
        Request $request,
        $response,
        $expectedCacheControlHeader,
        $expectedEtag,
        $expectedLastModified
    ) {
        $cacheableResponse = $this->cacheableResponseService->getCachableResponse($request, $response);

        $this->assertInstanceOf(Response::class, $cacheableResponse);

        $this->assertEquals($expectedCacheControlHeader, $cacheableResponse->headers->get('cache-control'));
        $this->assertEquals($expectedEtag, $cacheableResponse->getEtag());
        $this->assertEquals($expectedLastModified, $cacheableResponse->getLastModified());
    }

    /**
     * @return array
     */
    public function getCacheableResponseDataProvider()
    {
        return [
            'no etag, has last modified' => [
                'request' => $this->createRequest([
                    'x-cache-validator-lastmodified' => '2018-01-08 11:00:00',
                ]),
                'response' => null,
                'expectedCacheControlHeader' => 'must-revalidate, public',
                'expectedEtag' => null,
                'expectedLastModified' => new \DateTime('2018-01-08 11:00:00'),
            ],
            'has etag, has last modified' => [
                'request' => $this->createRequest([
                    'x-cache-validator-etag' => 'foo',
                    'x-cache-validator-lastmodified' => '2018-01-08 10:00:00',
                ]),
                'response' => null,
                'expectedCacheControlHeader' => 'must-revalidate, public',
                'expectedEtag' => '"foo"',
                'expectedLastModified' => new \DateTime('2018-01-08 10:00:00'),
            ],
        ];
    }

    public function testGetUncacheableResponse()
    {
        $uncacheableResponse = $this->cacheableResponseService->getUncacheableResponse(new Response());

        $this->assertInstanceOf(Response::class, $uncacheableResponse);

        $this->assertEquals(
            'max-age=0, must-revalidate, no-cache, public',
            $uncacheableResponse->headers->get('cache-control')
        );
    }

    /**
     * @param array $headers
     * @return Request
     */
    private function createRequest(array $headers = [])
    {
        $request = new Request();

        if (!empty($headers)) {
            foreach ($headers as $key => $value) {
                $request->headers->set($key, $value);
            }
        }

        return $request;
    }
}
