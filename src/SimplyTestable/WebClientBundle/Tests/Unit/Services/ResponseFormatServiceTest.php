<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Services\CacheableResponseService;
use SimplyTestable\WebClientBundle\Services\ResponseFormatService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use webignition\InternetMediaType\InternetMediaType;

class ResponseFormatServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseFormatService
     */
    private $responseFormatService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->responseFormatService = new ResponseFormatService();
    }

    /**
     * @dataProvider getRequestedResponseFormatDataProvider
     *
     * @param Request $request
     * @param string $expectedRequestedResponseFormat
     */
    public function testGetRequestedResponseFormat(Request $request, $expectedRequestedResponseFormat)
    {
        $this->responseFormatService->setRequest($request);

        $requestedResponseFormat = $this->responseFormatService->getRequestedResponseFormat();

        $this->assertInstanceOf(InternetMediaType::class, $requestedResponseFormat);
        $this->assertEquals($expectedRequestedResponseFormat, $requestedResponseFormat->__toString());
    }

    /**
     * @return array
     */
    public function getRequestedResponseFormatDataProvider()
    {
        return [
            'no request format attribute, use default' => [
                'request' => new Request(),
                'expectedRequestedResponseFormat' => 'text/html',
            ],
            'invalid request format attribute' => [
                'request' => new Request([], [], [
                    '_format' => 'foo/bar',
                ]),
                'expectedRequestedResponseFormat' => 'text/html',
            ],
            'valid request format attribute' => [
                'request' => new Request([], [], [
                    '_format' => 'json',
                ]),
                'expectedRequestedResponseFormat' => 'application/json',
            ],
            'accept header text/html' => [
                'request' => $this->createRequestWithHeaders([
                    'accept' => 'text/html',
                ]),
                'expectedRequestedResponseFormat' => 'text/html',
            ],
            'accept header application/json' => [
                'request' => $this->createRequestWithHeaders([
                    'accept' => 'application/json',
                ]),
                'expectedRequestedResponseFormat' => 'application/json',
            ],
            'accept header */*' => [
                'request' => $this->createRequestWithHeaders([
                    'accept' => '*/*',
                ]),
                'expectedRequestedResponseFormat' => 'text/html',
            ],
        ];
    }

    public function testIsDefaultResponseFormat()
    {
        $this->responseFormatService->setRequest(new Request());
        $this->assertTrue($this->responseFormatService->isDefaultResponseFormat());
    }

    /**
     * @dataProvider hasAllowedResponseFormatDataProvider
     *
     * @param Request $request
     * @param bool $expectedHasAllowed
     */
    public function testHasAllowedResponseFormat(Request $request, $expectedHasAllowed)
    {
        $this->responseFormatService->setRequest($request);

        $hasAllowed = $this->responseFormatService->hasAllowedResponseFormat();

        $this->assertEquals($expectedHasAllowed, $hasAllowed);
    }

    /**
     * @return array
     */
    public function hasAllowedResponseFormatDataProvider()
    {
        return [
            'no request format attribute, use default' => [
                'request' => new Request(),
                'expectedHasAllowed' => false,
            ],
            'invalid request format attribute' => [
                'request' => new Request([], [], [
                    '_format' => 'foo/bar',
                ]),
                'expectedHasAllowed' => false,
            ],
            'valid request format attribute' => [
                'request' => new Request([], [], [
                    '_format' => 'json',
                ]),
                'expectedHasAllowed' => true,
            ],
        ];
    }

    public function testSetAllowedContentTypes()
    {
        $reflectionClass = new ReflectionClass(ResponseFormatService::class);

        $reflectionProperty = $reflectionClass->getProperty('allowedContentTypes');
        $reflectionProperty->setAccessible(true);

        $allowedContentTypes = $reflectionProperty->getValue($this->responseFormatService);
        $this->assertEquals([], $allowedContentTypes);

        $this->responseFormatService->setAllowedContentTypes([
            'foo/bar',
        ]);

        $allowedContentTypes = $reflectionProperty->getValue($this->responseFormatService);
        $this->assertEquals(['foo/bar'], $allowedContentTypes);
    }

    /**
     * @param array $headers
     *
     * @return Request
     */
    private function createRequestWithHeaders(array $headers)
    {
        $request = new Request();

        foreach ($headers as $key => $value) {
            $request->headers->set($key, $value);
        }

        return $request;
    }
}
