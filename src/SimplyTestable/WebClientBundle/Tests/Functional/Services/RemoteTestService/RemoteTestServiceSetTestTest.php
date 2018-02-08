<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\RemoteTestService;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceSetTestTest extends AbstractRemoteTestServiceTest
{
    const REMOTE_TEST_ID = 1;
    /**
     * @var Test
     */
    private $test;

    /**
     * @var RemoteTest
     */
    private $remoteTest;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->test = new Test();
        $this->test->setTestId(self::REMOTE_TEST_ID);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->remoteTest = new RemoteTest([
            'id' => self::REMOTE_TEST_ID,
        ]);
    }

    /**
     * @dataProvider setTestDataProvider
     *
     * @param array $httpFixtures
     * @param bool $expectedHasRemoteTest
     *
     * @throws WebResourceException
     */
    public function testSetTest(
        array $httpFixtures,
        $expectedHasRemoteTest
    ) {
        $this->setHttpFixtures($httpFixtures);
        $this->remoteTestService->setUser($this->user);

        $this->remoteTestService->setTest($this->test);

        if ($expectedHasRemoteTest) {
            $this->assertEquals($this->remoteTest, $this->getRemoteTestServiceRemoteTest());
        } else {
            $this->assertNull($this->getRemoteTestServiceRemoteTest());
        }
    }

    /**
     * @return array
     */
    public function setTestDataProvider()
    {
        return [
            'remote test matches local test' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                            'id' => self::REMOTE_TEST_ID,
                        ])),
                ],
                'expectedHasRemoteTest' => true,
            ],
            'remote test does not match local test' => [
                'httpFixtures' => [
                    Response::fromMessage("HTTP/1.1 200\nContent-type:application/json\n\n" . json_encode([
                        'id' => self::REMOTE_TEST_ID - 1,
                    ])),
                ],
                'expectedHasRemoteTest' => false,
            ],
        ];
    }
}
