<?php

namespace Tests\AppBundle\Functional\Services\RemoteTestService;

use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
use Tests\AppBundle\Factory\ConnectExceptionFactory;
use Tests\AppBundle\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceGetTest extends AbstractRemoteTestServiceTest
{
    /**
     * @var Test
     */
    private $test;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->test = new Test();
        $this->test->setTestId(1);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($this->test);
    }

    /**
     * @dataProvider getRemoteFailureDataProvider
     *
     * @param array $httpFixtures
     * @param string $expectedException
     * @param string $expectedExceptionMessage
     * @param string $expectedExceptionCode
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testGetRemoteFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        $this->expectException($expectedException);
        $this->expectExceptionMessage($expectedExceptionMessage);
        $this->expectExceptionCode($expectedExceptionCode);

        $this->remoteTestService->get();
    }

    /**
     * @return array
     */
    public function getRemoteFailureDataProvider()
    {
        $internalServerErrorResponse = HttpResponseFactory::createInternalServerErrorResponse();
        $curlTimeoutConnectException = ConnectExceptionFactory::create('CURL/28 Operation timed out');

        return [
            'HTTP 404' => [
                'httpFixtures' => [
                    HttpResponseFactory::createNotFoundResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                    $internalServerErrorResponse,
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
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
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testGetRemoteTestNotJsonDocument()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createSuccessResponse([
                'content-type' => 'text/plain',
            ]),
        ]);

        $test = new Test();
        $test->setTestId(1);
        $test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($test);

        $remoteTest = $this->remoteTestService->get();

        $this->assertFalse($remoteTest);
    }

    public function testGetSuccess()
    {
        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => 1,
            ]),
        ]);

        $test = new Test();
        $test->setTestId(1);
        $test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($test);

        $remoteTest = $this->remoteTestService->get();

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
        $this->assertEquals('http://null/job/http%3A%2F%2Fexample.com%2F/1/', $this->httpHistory->getLastRequestUrl());
    }
}
