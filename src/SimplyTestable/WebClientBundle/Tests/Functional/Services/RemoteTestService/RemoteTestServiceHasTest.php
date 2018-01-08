<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceHasTest extends AbstractRemoteTestServiceTest
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
    }

    public function testHasNoRemoteTest()
    {
        $this->assertFalse($this->remoteTestService->has());
    }

    public function testHasNoLocalTest()
    {
        $remoteTest = new RemoteTest(new \stdClass());
        $this->remoteTestService->set($remoteTest);

        $this->assertFalse($this->remoteTestService->has());
    }

    public function testHas()
    {
        $remoteTestId = 2;

        $remoteTestData = new \stdClass();
        $remoteTestData->id = $remoteTestId;

        $this->test->setTestId($remoteTestId);
        $this->setRemoteTestServiceTest($this->test);

        $remoteTest = new RemoteTest($remoteTestData);
        $this->remoteTestService->set($remoteTest);

        $this->assertTrue($this->remoteTestService->has());
    }

//    /**
//     * @dataProvider getRemoteFailureDataProvider
//     *
//     * @param array $httpFixtures
//     * @param string $expectedException
//     * @param string $expectedExceptionMessage
//     * @param string $expectedExceptionCode
//     *
//     * @throws WebResourceException
//     */
//    public function testGetRemoteFailure(
//        array $httpFixtures,
//        $expectedException,
//        $expectedExceptionMessage,
//        $expectedExceptionCode
//    ) {
//        $this->setHttpFixtures($httpFixtures);
//        $this->remoteTestService->setUser($this->user);
//
//        $this->setExpectedException($expectedException, $expectedExceptionMessage, $expectedExceptionCode);
//        $this->remoteTestService->get();
//    }
//
//    /**
//     * @return array
//     */
//    public function getRemoteFailureDataProvider()
//    {
//        return [
//            'HTTP 404' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 404'),
//                ],
//                'expectedException' => WebResourceException::class,
//                'expectedExceptionMessage' => 'Not Found',
//                'expectedExceptionCode' => 404,
//            ],
//            'HTTP 500' => [
//                'httpFixtures' => [
//                    Response::fromMessage('HTTP/1.1 500'),
//                ],
//                'expectedException' => WebResourceException::class,
//                'expectedExceptionMessage' => 'Internal Server Error',
//                'expectedExceptionCode' => 500,
//            ],
//            'CURL 28' => [
//                'httpFixtures' => [
//                    CurlExceptionFactory::create('Operation timed out', 28),
//                ],
//                'expectedException' => CurlException::class,
//                'expectedExceptionMessage' => '',
//                'expectedExceptionCode' => 0,
//            ],
//        ];
//    }
//
//    public function testGetRemoteTestNotJsonDocument()
//    {
//        $this->setHttpFixtures([
//            Response::fromMessage("HTTP/1.1 200\nContent-type:text/plain\n\n"),
//        ]);
//        $this->remoteTestService->setUser($this->user);
//
//        $test = new Test();
//        $test->setTestId(1);
//        $test->setWebsite(new NormalisedUrl('http://example.com/'));
//
//        $this->setRemoteTestServiceTest($test);
//
//        $remoteTest = $this->remoteTestService->get();
//
//        $this->assertFalse($remoteTest);
//    }
//
//    public function testGetSuccess()
//    {
//        $this->setHttpFixtures([
//            Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
//                'id' => 1,
//            ])),
//        ]);
//        $this->remoteTestService->setUser($this->user);
//
//        $test = new Test();
//        $test->setTestId(1);
//        $test->setWebsite(new NormalisedUrl('http://example.com/'));
//
//        $this->setRemoteTestServiceTest($test);
//
//        $remoteTest = $this->remoteTestService->get();
//
//        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
//    }
}
