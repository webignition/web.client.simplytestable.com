<?php

namespace Tests\WebClientBundle\Functional\Services\RemoteTestService;

use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use Tests\WebClientBundle\Factory\HttpResponseFactory;
use webignition\NormalisedUrl\NormalisedUrl;

class RemoteTestServiceRetestTest extends AbstractRemoteTestServiceTest
{
    const TEST_ID = 1;
    const NEW_TEST_ID = 2;

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
        $this->test->setTestId(self::TEST_ID);
        $this->test->setWebsite(new NormalisedUrl('http://example.com/'));

        $this->setRemoteTestServiceTest($this->test);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::NEW_TEST_ID,
            ]),
        ]);
    }

    public function testRetest()
    {
        $remoteTest = $this->remoteTestService->retest($this->test->getTestId(), $this->test->getWebsite());

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
        $this->assertEquals(self::NEW_TEST_ID, $remoteTest->getId());

        $this->assertEquals(
            'http://null/job/http%3A%2F%2Fexample.com%2F/1/re-test/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
