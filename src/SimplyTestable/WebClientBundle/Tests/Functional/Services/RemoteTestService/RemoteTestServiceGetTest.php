<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use Guzzle\Plugin\History\HistoryPlugin;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Tests\Factory\CurlExceptionFactory;
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
     * @throws WebResourceException
     */
    public function testGetRemoteFailure(
        array $httpFixtures,
        $expectedException,
        $expectedExceptionMessage,
        $expectedExceptionCode
    ) {
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

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
                    Response::fromMessage('HTTP/1.1 404'),
                ],
                'expectedException' => WebResourceException::class,
                'expectedExceptionMessage' => 'Not Found',
                'expectedExceptionCode' => 404,
            ],
            'HTTP 500' => [
                'httpFixtures' => [
                    Response::fromMessage('HTTP/1.1 500'),
                ],
                'expectedException' => WebResourceException::class,
                'expectedExceptionMessage' => 'Internal Server Error',
                'expectedExceptionCode' => 500,
            ],
            'CURL 28' => [
                'httpFixtures' => [
                    CurlExceptionFactory::create('Operation timed out', 28),
                ],
                'expectedException' => CurlException::class,
                'expectedExceptionMessage' => '',
                'expectedExceptionCode' => 0,
            ],
        ];
    }

    public function testGetRemoteTestNotJsonDocument()
    {
        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:text/plain\n\n"),
        ]);
        $this->remoteTestService->setUser($this->user);

        $test = new Test();
        $test->setTestId(1);
        $test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($test);

        $remoteTest = $this->remoteTestService->get();

        $this->assertFalse($remoteTest);
    }

    public function testGetSuccess()
    {
        $httpHistoryPlugin = new HistoryPlugin();

        $httpClientService = $this->getHttpClientService();
        $httpClientService->get()->addSubscriber($httpHistoryPlugin);

        $this->setHttpFixtures([
            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                'id' => 1,
            ])),
        ]);

        $this->remoteTestService->setUser($this->user);

        $test = new Test();
        $test->setTestId(1);
        $test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($test);

        $remoteTest = $this->remoteTestService->get();

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);

        $lastRequest = $httpHistoryPlugin->getLastRequest();

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/',
            $lastRequest->getUrl()
        );
    }
}
