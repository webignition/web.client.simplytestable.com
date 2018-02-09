<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\HttpResponseFactory;
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
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);
        $this->remoteTestService->get();
    }

    /**
     * @return array
     */
    public function getRemoteFailureDataProvider()
    {
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
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                    HttpResponseFactory::createInternalServerErrorResponse(),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedException' => CoreApplicationRequestException::class,
                'expectedExceptionMessage' => 'Operation timed out',
                'expectedExceptionCode' => 28,
            ],
        ];
    }

    public function testGetRemoteTestNotJsonDocument()
    {
        $this->setCoreApplicationHttpClientHttpFixtures([
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
        $this->setCoreApplicationHttpClientHttpFixtures([
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
        $this->assertEquals('http://null/job/http%3A%2F%2Fexample.com%2F/1/', $this->getLastRequest()->getUrl());
    }
}
