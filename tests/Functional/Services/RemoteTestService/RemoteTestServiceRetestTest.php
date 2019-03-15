<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;
use App\Tests\Factory\HttpResponseFactory;

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

        $this->test = Test::create(1);

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::NEW_TEST_ID,
            ]),
        ]);
    }

    public function testRetest()
    {
        $remoteTest = $this->remoteTestService->retest($this->test->getTestId());

        $this->assertInstanceOf(RemoteTest::class, $remoteTest);
        $this->assertEquals(self::NEW_TEST_ID, $remoteTest->getId());

        $this->assertEquals(
            'http://null/job/1/re-test/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
