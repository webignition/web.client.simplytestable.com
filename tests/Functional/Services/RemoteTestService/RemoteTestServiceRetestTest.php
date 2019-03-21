<?php

namespace App\Tests\Functional\Services\RemoteTestService;

use App\Model\TestIdentifier;
use App\Tests\Factory\HttpResponseFactory;

class RemoteTestServiceRetestTest extends AbstractRemoteTestServiceTest
{
    const TEST_ID = 1;
    const NEW_TEST_ID = 2;
    const WEBSITE = 'http://example.com/';

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->httpMockHandler->appendFixtures([
            HttpResponseFactory::createJsonResponse([
                'id' => self::NEW_TEST_ID,
                'website' => self::WEBSITE,
            ]),
        ]);
    }

    public function testRetest()
    {
        $testIdentifier = $this->remoteTestService->retest(self::TEST_ID);

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
