<?php

namespace App\Tests\Unit\Services;

use Psr\Http\Message\ResponseInterface;
use App\Exception\InvalidContentTypeException;
use App\Services\JsonResponseHandler;
use App\Tests\Factory\HttpResponseFactory;

class JsonResponseHandlerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->jsonResponseHandler = new JsonResponseHandler();
    }

    public function testHandleInvalidContentType()
    {
        $this->expectException(InvalidContentTypeException::class);

        $this->jsonResponseHandler->handle(HttpResponseFactory::createSuccessResponse());
    }

    /**
     * @dataProvider handleSuccessDataProvider
     *
     * @param ResponseInterface $response
     * @param mixed $expectedResponseData
     *
     * @throws InvalidContentTypeException
     */
    public function testHandleSuccess(ResponseInterface $response, $expectedResponseData)
    {
        $responseData = $this->jsonResponseHandler->handle($response);

        $this->assertEquals($expectedResponseData, $responseData);
    }

    /**
     * @return array
     */
    public function handleSuccessDataProvider()
    {
        return [
            'boolean true' => [
                'response' => HttpResponseFactory::createJsonResponse(true),
                'expectedResponseData' => true,
            ],
            'boolean false' => [
                'response' => HttpResponseFactory::createJsonResponse(false),
                'expectedResponseData' => false,
            ],
            'string' => [
                'response' => HttpResponseFactory::createJsonResponse('foo'),
                'expectedResponseData' => 'foo',
            ],
            'integer' => [
                'response' => HttpResponseFactory::createJsonResponse(123),
                'expectedResponseData' => 123,
            ],
            'array' => [
                'response' => HttpResponseFactory::createJsonResponse([
                    'foo' => 'bar',
                ]),
                'expectedResponseData' => [
                    'foo' => 'bar',
                ],
            ],
        ];
    }
}
