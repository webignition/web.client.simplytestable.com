<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
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
     * @throws \ReflectionException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     */
    public function testSetTest(
        array $httpFixtures,
        $expectedHasRemoteTest
    ) {
        $this->setCoreApplicationHttpClientHttpFixtures($httpFixtures);

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
                    HttpResponseFactory::createJsonResponse([
                        'id' => self::REMOTE_TEST_ID,
                    ]),
                ],
                'expectedHasRemoteTest' => true,
            ],
            'remote test does not match local test' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => (self::REMOTE_TEST_ID - 1),
                    ]),
                ],
                'expectedHasRemoteTest' => false,
            ],
        ];
    }
}