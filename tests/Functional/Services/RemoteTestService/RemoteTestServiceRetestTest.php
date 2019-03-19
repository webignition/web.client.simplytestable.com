<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Entity\Test;
use App\Model\TestIdentifier;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceRetestTest extends AbstractRemoteTestServiceTest
{
    const TEST_ID = 1;
    const NEW_TEST_ID = 2;
    const WEBSITE = 'http://example.com/';

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
                'website' => self::WEBSITE,
            ]),
        ]);
    }

    public function testRetest()
    {
        $testIdentifier = $this->remoteTestService->retest($this->test->getTestId());

        $this->assertInstanceOf(TestIdentifier::class, $testIdentifier);

        $this->assertEquals(
            [
                'test_id' => self::NEW_TEST_ID,
                'website' => self::WEBSITE,
            ],
            $testIdentifier->toArray()
        );

        $this->assertEquals(
            'http://null/job/1/re-test/',
            $this->httpHistory->getLastRequestUrl()
        );
    }
}
