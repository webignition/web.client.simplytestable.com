<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Entity\Task\Task;
use App\Entity\Test;
use App\Services\TestService;
use App\Tests\Factory\HttpResponseFactory;
use App\Tests\Factory\TestFactory;
use App\Tests\Services\ObjectReflector;
use Psr\Http\Message\ResponseInterface;

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
     * @dataProvider getReturnsNullDataProvider
     */
    public function testGetReturnsNull(ResponseInterface $httpResponse)
    {
        $this->httpMockHandler->appendFixtures([$httpResponse]);

        $this->assertNull($this->testService->get(1));
    }

    public function getReturnsNullDataProvider(): array
    {
        return [
            'not authorised' => [
                'httpResponse' => HttpResponseFactory::createForbiddenResponse(),
            ],
            'invalid response content type' => [
                'httpResponse' => HttpResponseFactory::createSuccessResponse(),
            ],
        ];
    }

    /**
     * @dataProvider getSuccessDataProvider
     */
    public function testGetSuccess(
        array $httpFixtures,
        array $testValues,
        int $testId,
        array $expectedTestValues
    ) {
        $this->httpMockHandler->appendFixtures($httpFixtures);

        if (!empty($testValues)) {
            $testFactory = new TestFactory(self::$container);
            $testFactory->create($testValues);
        }

        $test = $this->testService->get($testId);

        $this->assertInstanceOf(Test::class, $test);

        $this->assertEquals($expectedTestValues['testId'], $test->getTestId());
        $this->assertEquals($expectedTestValues['state'], $test->getState());
        $this->assertEquals($expectedTestValues['website'], $test->getWebsite());
        $this->assertEquals($expectedTestValues['urlCount'], $test->getUrlCount());
        $this->assertEquals($expectedTestValues['type'], ObjectReflector::getProperty($test, 'type'));
        $this->assertEquals($expectedTestValues['taskTypes'], ObjectReflector::getProperty($test, 'taskTypes'));
        $this->assertEquals($expectedTestValues['taskIds'], $test->getTaskIds());
    }

    public function getSuccessDataProvider(): array
    {
        return [
            'has not locally, remote state not prepared' => [
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
                        'state' => Test::STATE_STARTING,
                        'type' => Test::TYPE_FULL_SITE,
                    ])
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                ],
                'testId' => 1,
                'expectedTestValues' => [
                    'testId' => 1,
                    'state' => Test::STATE_STARTING,
                    'website' => 'http://example.com/',
                    'urlCount' => 0,
                    'type' => Test::TYPE_FULL_SITE,
                    'taskTypes' => [
                        Task::TYPE_HTML_VALIDATION,
                        Task::TYPE_CSS_VALIDATION,
                    ],
                    'taskIds' => [],
                ],
            ],
            'has locally, not has task ids, is finished' => [
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
                    HttpResponseFactory::createJsonResponse([
                        1, 2, 3,
                    ])
                ],
                'testValues' => [
                    TestFactory::KEY_WEBSITE => 'http://example.com/',
                    TestFactory::KEY_TEST_ID => 1,
                ],
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
                    'taskIds' => [1, 2, 3],
                ],
            ],
            'has locally, has task ids, is finished' => [
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
                    TestFactory::KEY_TASK_IDS => '1,2,3',
                ],
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
                    'taskIds' => [1, 2, 3],
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
                    HttpResponseFactory::createJsonResponse([
                        4, 5, 6,
                    ])
                ],
                'testValues' => [],
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
                    'taskIds' => [4, 5, 6],
                ],
            ],
        ];
    }
}
