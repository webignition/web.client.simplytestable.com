<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Entity\TimePeriod;
use App\Services\TestService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;

class TestServiceTest extends AbstractCoreApplicationServiceTest
{
    const USERNAME = 'user@example.com';

    /**
     * @var TestService
     */
    private $testService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testService = self::$container->get(TestService::class);
    }

    /**
     * @dataProvider hasDataProvider
     */
    public function testHas(
        array $httpFixtures,
        array $testValues,
        string $canonicalUrl,
        int $testId,
        bool $expectedHas
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        $has = $this->testService->has($canonicalUrl, $testId);

        $this->assertEquals($expectedHas, $has);
    }

    public function hasDataProvider(): array
    {
        return [
            'has locally' => [
                'httpFixtures' => [],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => true,
            ],
            'has remotely' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                    ]),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => true,
            ],
            'not has remotely' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedHas' => false,
            ],
        ];
    }

    /**
     * @dataProvider getDataProvider
     */
    public function testGet(
        array $httpFixtures,
        array $testValues,
        string $canonicalUrl,
        int $testId,
        ?array $expectedTestValues
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        $test = $this->testService->get($canonicalUrl, $testId);

        if (empty($expectedTestValues)) {
            $this->assertFalse($test);
        } else {
            $this->assertEquals($expectedTestValues['testId'], $test->getTestId());
            $this->assertEquals($expectedTestValues['state'], $test->getState());
            $this->assertEquals($expectedTestValues['website'], $test->getWebsite());
            $this->assertEquals($expectedTestValues['urlCount'], $test->getUrlCount());
            $this->assertEquals($expectedTestValues['type'], $test->getType());
            $this->assertEquals($expectedTestValues['taskTypes'], $test->getTaskTypes());

            $timePeriod = $test->getTimePeriod();

            $this->assertInstanceOf(TimePeriod::class, $timePeriod);
            $this->assertEquals($expectedTestValues['timePeriod']['startDateTime'], $timePeriod->getStartDateTime());
            $this->assertEquals($expectedTestValues['timePeriod']['endDateTime'], $timePeriod->getEndDateTime());
        }
    }

    public function getDataProvider(): array
    {
        return [
            'has locally, update, is finished' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [
                            [
                                'name' => Task::TYPE_HTML_VALIDATION,
                            ],
                            [
                                'name' => Task::TYPE_CSS_VALIDATION,
                            ],
                        ],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                        'url_count' => 99,
                        'type' => Test::TYPE_FULL_SITE,
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_TYPE => Test::TYPE_FULL_SITE,
                ],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedTestValues' => [
                    'testId' => 1,
                    'state' => Test::STATE_COMPLETED,
                    'website' => 'http://example.com/',
                    'urlCount' => 99,
                    'type' => Test::TYPE_FULL_SITE,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                        Task::TYPE_CSS_VALIDATION,
                    ],
                    'timePeriod' => [
                        'startDateTime' => null,
                        'endDateTime' => null,
                    ],
                ],
            ],
            'has locally, update, state change in-progess->completed' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [
                            [
                                'name' => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                        'url_count' => 99,
                        'type' => Test::TYPE_FULL_SITE,
                        'time_period' => [
                            'start_date_time' => (new \DateTime('2010-01-01 00:00:00'))->format(DATE_ATOM),
                            'end_date_time' => (new \DateTime('2010-01-01 00:01:00'))->format(DATE_ATOM),
                        ],
                    ]),
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                    TestFactory::KEY_TYPE => Test::TYPE_FULL_SITE,
                    TestFactory::KEY_STATE => Test::STATE_IN_PROGRESS,
                ],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedTestValues' => [
                    'testId' => 1,
                    'state' => Test::STATE_COMPLETED,
                    'website' => 'http://example.com/',
                    'urlCount' => 99,
                    'type' => Test::TYPE_FULL_SITE,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    'timePeriod' => [
                        'startDateTime' => new \DateTime('2010-01-01 00:00:00'),
                        'endDateTime' => new \DateTime('2010-01-01 00:01:00'),
                    ],
                ],
            ],
            'has not locally, has remotely, create' => [
                'httpFixtures' => [
                    HttpResponseFactory::createJsonResponse([
                        'id' => 1,
                        'website' => 'http://example.com/',
                        'task_types' => [
                            [
                                'name' => Task::TYPE_HTML_VALIDATION,
                            ],
                        ],
                        'user' => self::USERNAME,
                        'state' => Test::STATE_COMPLETED,
                        'url_count' => 99,
                        'type' => Test::TYPE_FULL_SITE,
                    ]),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedTestValues' => [
                    'testId' => 1,
                    'state' => Test::STATE_COMPLETED,
                    'website' => 'http://example.com/',
                    'urlCount' => 99,
                    'type' => Test::TYPE_FULL_SITE,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                    ],
                    'timePeriod' => [
                        'startDateTime' => null,
                        'endDateTime' => null,
                    ],
                ],
            ],
            'has not locally, has not remotely, do not create' => [
                'httpFixtures' => [
                    HttpResponseFactory::createForbiddenResponse(),
                ],
                'testValues' => [],
                'canonicalUrl' => 'http://example.com/',
                'testId' => 1,
                'expectedTestValues' => null,
            ],
        ];
    }

    /**
     * @dataProvider isFinishedDataProvider
     */
    public function testIsFinished(string $state, bool $expectedIsFinished)
    {
        $test = new Test();
        $test->setState($state);

        $this->assertEquals($expectedIsFinished, $this->testService->isFinished($test));
    }

    public function isFinishedDataProvider(): array
    {
        return [
            Test::STATE_STARTING => [
                'state' => Test::STATE_STARTING,
                'isFinished' => false,
            ],
            Test::STATE_CANCELLED => [
                'state' => Test::STATE_CANCELLED,
                'isFinished' => true,
            ],
            Test::STATE_COMPLETED => [
                'state' => Test::STATE_COMPLETED,
                'isFinished' => true,
            ],
            Test::STATE_IN_PROGRESS => [
                'state' => Test::STATE_IN_PROGRESS,
                'isFinished' => false,
            ],
            Test::STATE_PREPARING => [
                'state' => Test::STATE_PREPARING,
                'isFinished' => false,
            ],
            Test::STATE_QUEUED => [
                'state' => Test::STATE_QUEUED,
                'isFinished' => false,
            ],
            Test::STATE_FAILED_NO_SITEMAP => [
                'state' => Test::STATE_FAILED_NO_SITEMAP,
                'isFinished' => true,
            ],
            Test::STATE_REJECTED => [
                'state' => Test::STATE_REJECTED,
                'isFinished' => true,
            ],
            Test::STATE_RESOLVING => [
                'state' => Test::STATE_RESOLVING,
                'isFinished' => false,
            ],
            Test::STATE_RESOLVED => [
                'state' => Test::STATE_RESOLVED,
                'isFinished' => false,
            ],
            Test::STATE_CRAWLING => [
                'state' => Test::STATE_CRAWLING,
                'isFinished' => false,
            ],
        ];
    }
}
