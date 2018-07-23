<?php

namespace Tests\AppBundle\Unit\Model\RemoteTest;

use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Entity\TimePeriod;
use App\Model\RemoteTest\Rejection;
use App\Model\RemoteTest\RemoteTest;
use Symfony\Component\HttpFoundation\ParameterBag;

class RemoteTestTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getStateDataProvider
     *
     * @param array $remoteTestData
     * @param string $expectedState
     */
    public function testGetState(array $remoteTestData, $expectedState)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $this->assertEquals($expectedState, $remoteTest->getState());
    }

    /**
     * @return array
     */
    public function getStateDataProvider()
    {
        return [
            'non-crawling state' => [
                'remoteTestData' => [
                    'state' => Test::STATE_IN_PROGRESS,
                ],
                'expectedState' => Test::STATE_IN_PROGRESS,
            ],
            'crawling' => [
                'remoteTestData' => [
                    'state' => Test::STATE_FAILED_NO_SITEMAP,
                    'crawl' => [
                        'foo' => 'bar',
                    ],
                ],
                'expectedState' => Test::STATE_CRAWLING,
            ],
        ];
    }

    public function testGetScalarValues()
    {
        $type = Test::TYPE_FULL_SITE;
        $urlCount = 10;
        $taskCount = 1000;
        $user = 'user@example.com';
        $isPublic = false;
        $erroredTaskCount = 30;
        $cancelledTaskCount = 40;
        $skippedTaskCount = 50;
        $warningedTaskCount = 60;
        $website = 'http://example.com/';
        $id = 999;
        $ammendments = [];

        $remoteTestData = [
            'type' => $type,
            'url_count' => $urlCount,
            'task_count' => $taskCount,
            'user' => $user,
            'is_public' => $isPublic,
            'errored_task_count' => $erroredTaskCount,
            'cancelled_task_count' => $cancelledTaskCount,
            'skipped_task_count' => $skippedTaskCount,
            'warninged_task_count' => $warningedTaskCount,
            'website' => $website,
            'id' => $id,
            'ammendments' => $ammendments,
        ];

        $remoteTest = new RemoteTest($remoteTestData);

        $this->assertEquals($type, $remoteTest->getType());
        $this->assertEquals($urlCount, $remoteTest->getUrlCount());
        $this->assertEquals($taskCount, $remoteTest->getTaskCount());
        $this->assertEquals($user, $remoteTest->getUser());
        $this->assertEquals($isPublic, $remoteTest->getIsPublic());
        $this->assertEquals($erroredTaskCount, $remoteTest->getErroredTaskCount());
        $this->assertEquals($cancelledTaskCount, $remoteTest->getCancelledTaskCount());
        $this->assertEquals($skippedTaskCount, $remoteTest->getSkippedTaskCount());
        $this->assertEquals($warningedTaskCount, $remoteTest->getWarningedTaskCount());
        $this->assertEquals(930, $remoteTest->getErrorFreeTaskCount());
        $this->assertEquals($website, $remoteTest->getWebsite());
        $this->assertEquals($id, $remoteTest->getId());
        $this->assertEquals($ammendments, $remoteTest->getAmmendments());
    }

    /**
     * @dataProvider getTimePeriodDataProvider
     *
     * @param array $remoteTestData
     * @param bool $expectedHasTimePeriod
     * @param bool $expectedHasStartDateTime
     * @param bool $expectedHasEndDateTime
     */
    public function testGetTimePeriod(
        array $remoteTestData,
        $expectedHasTimePeriod,
        $expectedHasStartDateTime,
        $expectedHasEndDateTime
    ) {
        $remoteTest = new RemoteTest($remoteTestData);

        $timePeriod = $remoteTest->getTimePeriod();

        if ($expectedHasTimePeriod) {
            $this->assertInstanceOf(TimePeriod::class, $timePeriod);

            $startDateTime = $timePeriod->getStartDateTime();
            $endDateTime = $timePeriod->getEndDateTime();

            if ($expectedHasStartDateTime) {
                $this->assertInstanceOf(\DateTime::class, $startDateTime);
            } else {
                $this->assertNull($startDateTime);
            }

            if ($expectedHasEndDateTime) {
                $this->assertInstanceOf(\DateTime::class, $endDateTime);
            } else {
                $this->assertNull($endDateTime);
            }
        } else {
            $this->assertNull($timePeriod);
        }
    }

    /**
     * @return array
     */
    public function getTimePeriodDataProvider()
    {
        return [
            'no time period' => [
                'remoteTestData' => [],
                'expectedHasTimePeriod' => false,
                'expectedHasStartDateTime' => false,
                'expectedHasEndDateTime' => false,
            ],
            'empty time period' => [
                'remoteTestData' => [
                    'time_period' => [],
                ],
                'expectedHasTimePeriod' => true,
                'expectedHasStartDateTime' => false,
                'expectedHasEndDateTime' => false,
            ],
            'time period with start_date_time' => [
                'remoteTestData' => [
                    'time_period' => [
                        'start_date_time' => (new \DateTime())->format('c'),
                    ],
                ],
                'expectedHasTimePeriod' => true,
                'expectedHasStartDateTime' => true,
                'expectedHasEndDateTime' => false,
            ],
            'time period with end_date_time' => [
                'remoteTestData' => [
                    'time_period' => [
                        'end_date_time' => (new \DateTime())->format('c'),
                    ],
                ],
                'expectedHasTimePeriod' => true,
                'expectedHasStartDateTime' => false,
                'expectedHasEndDateTime' => true,
            ],
            'time period with start_date_time and end_date_time' => [
                'remoteTestData' => [
                    'time_period' => [
                        'start_date_time' => (new \DateTime())->format('c'),
                        'end_date_time' => (new \DateTime())->format('c'),
                    ],
                ],
                'expectedHasTimePeriod' => true,
                'expectedHasStartDateTime' => true,
                'expectedHasEndDateTime' => true,
            ],
        ];
    }

    /**
     * @dataProvider getTaskTypesDataProvider
     *
     * @param array $remoteTestData
     * @param array $expectedTaskTypes
     */
    public function testGetTaskTypes(array $remoteTestData, array $expectedTaskTypes)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $this->assertEquals($expectedTaskTypes, $remoteTest->getTaskTypes());
    }

    /**
     * @return array
     */
    public function getTaskTypesDataProvider()
    {
        return [
            'no task types' => [
                'remoteTestData' => [
                    'task_types' => [],
                ],
                'expectedTaskTypes' => [],
            ],
            'has task types' => [
                'remoteTestData' => [
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                        [
                            'name' => Task::TYPE_CSS_VALIDATION,
                        ],
                    ],
                ],
                'expectedTaskTypes' => [
                    Task::TYPE_HTML_VALIDATION,
                    Task::TYPE_CSS_VALIDATION,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getOptionsDataProvider
     *
     * @param array $remoteTestData
     * @param array $expectedOptions
     */
    public function testGetOptions(array $remoteTestData, array $expectedOptions)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $options = $remoteTest->getOptions();

        $this->assertInstanceOf(ParameterBag::class, $options);
        $this->assertEquals($expectedOptions, $options->all());
    }

    /**
     * @return array
     */
    public function getOptionsDataProvider()
    {
        return [
            'no task types, no task type options' => [
                'remoteTestData' => [
                    'task_types' => [],
                    'task_type_options' => [],
                ],
                'expectedOptions' => [],
            ],
            'has task types' => [
                'remoteTestData' => [
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                        [
                            'name' => Task::TYPE_CSS_VALIDATION,
                        ],
                    ],
                    'task_type_options' => [],
                ],
                'expectedOptions' => [
                    Task::TYPE_KEY_HTML_VALIDATION => 1,
                    Task::TYPE_KEY_CSS_VALIDATION => 1,
                ],
            ],
            'has task types and task type options' => [
                'remoteTestData' => [
                    'task_types' => [
                        [
                            'name' => Task::TYPE_HTML_VALIDATION,
                        ],
                        [
                            'name' => Task::TYPE_CSS_VALIDATION,
                        ],
                    ],
                    'task_type_options' => [
                        Task::TYPE_HTML_VALIDATION => [
                            'html-foo' => 'html-bar',
                        ],
                        Task::TYPE_CSS_VALIDATION => [
                            'css-foo' => 'css-bar',
                        ],
                    ],
                ],
                'expectedOptions' => [
                    Task::TYPE_KEY_HTML_VALIDATION => 1,
                    Task::TYPE_KEY_CSS_VALIDATION => 1,
                    'html-validation-html-foo' => 'html-bar',
                    'css-validation-css-foo' => 'css-bar',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getParameterDataProvider
     *
     * @param array $remoteTestData
     * @param string $key
     * @param mixed $expectedValue
     */
    public function testGetParameter(array $remoteTestData, $key, $expectedValue)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $value = $remoteTest->getParameter($key);

        $this->assertEquals($expectedValue, $value);
    }

    /**
     * @return array
     */
    public function getParameterDataProvider()
    {
        return [
            'no parameters' => [
                'remoteTestData' => [],
                'key' => 'foo',
                'expectedValue' => null,
            ],
            'empty parameters' => [
                'remoteTestData' => [
                    'parameters' => '',
                ],
                'key' => 'foo',
                'expectedValue' => null,
            ],
            'has parameters' => [
                'remoteTestData' => [
                    'parameters' => json_encode([
                        'foo' => 'bar',
                    ]),
                ],
                'key' => 'foo',
                'expectedValue' => 'bar',
            ],
        ];
    }

    /**
     * @dataProvider hasParameterDataProvider
     *
     * @param array $remoteTestData
     * @param string $key
     * @param bool $expectedHas
     */
    public function testHasParameter(array $remoteTestData, $key, $expectedHas)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $has = $remoteTest->hasParameter($key);

        $this->assertEquals($expectedHas, $has);
    }

    /**
     * @return array
     */
    public function hasParameterDataProvider()
    {
        return [
            'no parameters' => [
                'remoteTestData' => [],
                'key' => 'foo',
                'expectedHas' => false,
            ],
            'has parameters' => [
                'remoteTestData' => [
                    'parameters' => json_encode([
                        'foo' => 'bar',
                    ]),
                ],
                'key' => 'foo',
                'expectedHas' => true,
            ],
        ];
    }

    /**
     * @dataProvider getTaskCountByStateDataProvider
     *
     * @param array $remoteTestData
     * @param array $expectedTaskCountByState
     */
    public function testGetTaskCountByState(array $remoteTestData, array $expectedTaskCountByState)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $taskCountByState = $remoteTest->getTaskCountByState();

        $this->assertEquals($expectedTaskCountByState, $taskCountByState);
    }

    /**
     * @return array
     */
    public function getTaskCountByStateDataProvider()
    {
        return [
            'no task counts' => [
                'remoteTestData' => [],
                'expectedTaskCountByState' => [
                    'in_progress' => 0,
                    Task::STATE_QUEUED => 0,
                    Task::STATE_COMPLETED => 0,
                    Task::STATE_CANCELLED => 0,
                    Task::STATE_FAILED => 0,
                    Task::STATE_SKIPPED => 0,
                ],
            ],
            'has task counts' => [
                'remoteTestData' => [
                    'task_count_by_state' => [
                        Task::STATE_IN_PROGRESS => 1,
                        Task::STATE_QUEUED => 2,
                        Task::STATE_QUEUED_FOR_ASSIGNMENT => 3,
                        Task::STATE_COMPLETED => 6,
                        Task::STATE_CANCELLED => 7,
                        Task::STATE_AWAITING_CANCELLATION => 8,
                        Task::STATE_FAILED => 9,
                        Task::STATE_FAILED_NO_RETRY_AVAILABLE => 9,
                        Task::STATE_FAILED_RETRY_AVAILABLE => 10,
                        Task::STATE_FAILED_RETRY_LIMIT_REACHED => 11,
                        Task::STATE_SKIPPED => 12,
                    ],
                ],
                'expectedTaskCountByState' => [
                    'in_progress' => 1,
                    Task::STATE_QUEUED => 5,
                    Task::STATE_COMPLETED => 6,
                    Task::STATE_CANCELLED => 15,
                    Task::STATE_FAILED => 39,
                    Task::STATE_SKIPPED => 12,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getCrawlDataProvider
     *
     * @param array $remoteTestData
     * @param array|null $expectedCrawlData
     */
    public function testGetCrawl(array $remoteTestData, $expectedCrawlData)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $crawlData = $remoteTest->getCrawl();

        $this->assertEquals($expectedCrawlData, $crawlData);
    }

    /**
     * @return array
     */
    public function getCrawlDataProvider()
    {
        return [
            'no crawl data' => [
                'remoteTestData' => [],
                'expectedCrawlData' => null,
            ],
            'has crawl data' => [
                'remoteTestData' => [
                    'crawl' => [
                        'id' => 1,
                    ],
                ],
                'expectedCrawlData' => [
                    'id' => 1,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getCompletionPercentDataProvider
     *
     * @param array $remoteTestData
     * @param int $expectedCompletionPercent
     */
    public function testGetCompletionPercent(array $remoteTestData, $expectedCompletionPercent)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $completionPercent = $remoteTest->getCompletionPercent();

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    /**
     * @return array
     */
    public function getCompletionPercentDataProvider()
    {
        return [
            'no remote test data' => [
                'remoteTestData' => [],
                'expectedCompletionPercent' => 0,
            ],
            'no remote test data, task_count=0' => [
                'remoteTestData' => [
                    'task_count' => 0,
                ],
                'expectedCompletionPercent' => 0,
            ],
            'crawling, no crawl data' => [
                'remoteTestData' => [
                    'state' => Test::STATE_CRAWLING,
                ],
                'expectedCompletionPercent' => 0,
            ],
            'crawling, no discovered urls' => [
                'remoteTestData' => [
                    'state' => Test::STATE_CRAWLING,
                    'crawl' => [
                        'discovered_url_count' => 0,
                    ],
                ],
                'expectedCompletionPercent' => 0,
            ],
            'crawling, has discovered urls; 50% complete' => [
                'remoteTestData' => [
                    'state' => Test::STATE_CRAWLING,
                    'crawl' => [
                        'discovered_url_count' => 100,
                        'limit' => 200,
                    ],
                ],
                'expectedCompletionPercent' => 50,
            ],
            'crawling, has discovered urls; foo% complete' => [
                'remoteTestData' => [
                    'state' => Test::STATE_CRAWLING,
                    'crawl' => [
                        'discovered_url_count' => 33,
                        'limit' => 40,
                    ],
                ],
                'expectedCompletionPercent' => 83,
            ],
            'all finished' => [
                'remoteTestData' => [
                    'task_count' => 8,
                    'task_count_by_state' => [
                        Task::STATE_IN_PROGRESS => 0,
                        Task::STATE_QUEUED => 0,
                        Task::STATE_QUEUED_FOR_ASSIGNMENT => 0,
                        Task::STATE_COMPLETED => 1,
                        Task::STATE_CANCELLED => 1,
                        Task::STATE_AWAITING_CANCELLATION => 1,
                        Task::STATE_FAILED => 1,
                        Task::STATE_FAILED_NO_RETRY_AVAILABLE => 1,
                        Task::STATE_FAILED_RETRY_AVAILABLE => 1,
                        Task::STATE_FAILED_RETRY_LIMIT_REACHED => 1,
                        Task::STATE_SKIPPED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 100,
            ],
            'partially completed, requiredPrecision=-1' => [
                'remoteTestData' => [
                    'task_count' => 8,
                    'task_count_by_state' => [
                        Task::STATE_COMPLETED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 10,
            ],
            'partially completed, requiredPrecision=0' => [
                'remoteTestData' => [
                    'task_count' => 10,
                    'task_count_by_state' => [
                        Task::STATE_COMPLETED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 10,
            ],
            'partially completed, requiredPrecision=1' => [
                'remoteTestData' => [
                    'task_count' => 100,
                    'task_count_by_state' => [
                        Task::STATE_COMPLETED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 1,
            ],
            'partially completed, requiredPrecision=2' => [
                'remoteTestData' => [
                    'task_count' => 1000,
                    'task_count_by_state' => [
                        Task::STATE_COMPLETED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 0.1,
            ],
            'partially completed, requiredPrecision=3' => [
                'remoteTestData' => [
                    'task_count' => 10000,
                    'task_count_by_state' => [
                        Task::STATE_COMPLETED => 1,
                    ],
                ],
                'expectedCompletionPercent' => 0.01,
            ],
        ];
    }

    /**
     * @dataProvider toArrayDataProvider
     *
     * @param array $remoteTestData
     * @param array $expectedArray
     */
    public function testToArray(array $remoteTestData, $expectedArray)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $array = $remoteTest->__toArray();

        $this->assertEquals($expectedArray, $array);
    }

    /**
     * @return array
     */
    public function toArrayDataProvider()
    {
        return [
//            'no remote test data' => [
//                'remoteTestData' => [],
//                'expectedArray' => [
//                    'task_count_by_state' => [
//                        'in_progress' => 0,
//                        Task::STATE_QUEUED => 0,
//                        Task::STATE_COMPLETED => 0,
//                        Task::STATE_CANCELLED => 0,
//                        Task::STATE_FAILED => 0,
//                        Task::STATE_SKIPPED => 0,
//                    ],
//                    'completion_percent' => 0,
//                ],
//            ],
//            'has remote test data; only arbitrary scalar values' => [
//                'remoteTestData' => [
//                    'foo' => 'bar',
//                ],
//                'expectedArray' => [
//                    'foo' => 'bar',
//                    'task_count_by_state' => [
//                        'in_progress' => 0,
//                        Task::STATE_QUEUED => 0,
//                        Task::STATE_COMPLETED => 0,
//                        Task::STATE_CANCELLED => 0,
//                        Task::STATE_FAILED => 0,
//                        Task::STATE_SKIPPED => 0,
//                    ],
//                    'completion_percent' => 0,
//                ],
//            ],
//            'has remote test data; only arbitrary object values' => [
//                'remoteTestData' => [
//                    'foo' => [
//                        'key' =>  'value',
//                    ],
//                ],
//                'expectedArray' => [
//                    'foo' => [
//                        'key' => 'value',
//                    ],
//                    'task_count_by_state' => [
//                        'in_progress' => 0,
//                        Task::STATE_QUEUED => 0,
//                        Task::STATE_COMPLETED => 0,
//                        Task::STATE_CANCELLED => 0,
//                        Task::STATE_FAILED => 0,
//                        Task::STATE_SKIPPED => 0,
//                    ],
//                    'completion_percent' => 0,
//                ],
//            ],
            'has task_type_options' => [
                'remoteTestData' => [
                    'task_type_options' => [
                        Task::TYPE_HTML_VALIDATION => [
                            'html-foo' => 'html-bar',
                        ],
                        Task::TYPE_CSS_VALIDATION => [
                            'css-foo' => 'css-bar',
                        ],
                    ],
                ],
                'expectedArray' => [
                    'task_count_by_state' => [
                        'in_progress' => 0,
                        Task::STATE_QUEUED => 0,
                        Task::STATE_COMPLETED => 0,
                        Task::STATE_CANCELLED => 0,
                        Task::STATE_FAILED => 0,
                        Task::STATE_SKIPPED => 0,
                    ],
                    'completion_percent' => 0,
                    'task_type_options' => [
                        Task::TYPE_HTML_VALIDATION => [
                            'html-foo' => 'html-bar',
                        ],
                        Task::TYPE_CSS_VALIDATION => [
                            'css-foo' => 'css-bar',
                        ],
                    ],
                ],
            ],
            'has ammendments' => [
                'remoteTestData' => [
                    'ammendments' => [
                        [
                            'reason' => 'reason-name',
                            'constraint' => [
                                'name' => 'constraint-name',
                                'limit' => 10,
                                'is_available' => true,
                            ],
                        ],
                    ],
                ],
                'expectedArray' => [
                    'task_count_by_state' => [
                        'in_progress' => 0,
                        Task::STATE_QUEUED => 0,
                        Task::STATE_COMPLETED => 0,
                        Task::STATE_CANCELLED => 0,
                        Task::STATE_FAILED => 0,
                        Task::STATE_SKIPPED => 0,
                    ],
                    'completion_percent' => 0,
                    'ammendments' => [
                        [
                            'reason' => 'reason-name',
                            'constraint' => [
                                'name' => 'constraint-name',
                                'limit' => 10,
                                'is_available' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getRejectionDataProvider
     *
     * @param array $remoteTestData
     * @param bool $expectedHasRejection
     */
    public function testGetRejection(array $remoteTestData, $expectedHasRejection)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $rejection = $remoteTest->getRejection();

        if ($expectedHasRejection) {
            $this->assertInstanceOf(Rejection::class, $rejection);
        } else {
            $this->assertNull($rejection);
        }
    }

    /**
     * @dataProvider getRejectionDataProvider
     *
     * @param array $remoteTestData
     * @param bool $expectedHasRejection
     */
    public function testHasRejection(array $remoteTestData, $expectedHasRejection)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $hasRejection = $remoteTest->hasRejection();
        $this->assertEquals($expectedHasRejection, $hasRejection);
    }

    /**
     * @return array
     */
    public function getRejectionDataProvider()
    {
        return [
            'no rejection' => [
                'remoteTestData' => [],
                'expectedHasRejection' => false,
            ],
            'has rejection' => [
                'remoteTestData' => [
                    'rejection' => [
                        'reason' => 'foo',
                        'constraint' => [
                            'name' => 'credits_per_month',
                            'limit' => 1000,
                        ],
                    ],
                ],
                'expectedHasRejection' => true,
            ],
        ];
    }

    /**
     * @dataProvider isTypeDataProvider
     *
     * @param string $type
     * @param bool $expectedIsFullSite
     * @param bool $expectedIsSingleUrl
     */
    public function testIsType($type, $expectedIsFullSite, $expectedIsSingleUrl)
    {
        $remoteTest = new RemoteTest([
            'type' => $type,
        ]);

        $this->assertEquals($expectedIsFullSite, $remoteTest->isFullSite());
        $this->assertEquals($expectedIsSingleUrl, $remoteTest->isSingleUrl());
    }

    /**
     * @return array
     */
    public function isTypeDataProvider()
    {
        return [
            'full site' => [
                'type' => Test::TYPE_FULL_SITE,
                'expectedIsFullSite' => true,
                'expectedIsSingleUrl' => false,
            ],
            'single url' => [
                'type' => Test::TYPE_SINGLE_URL,
                'expectedIsFullSite' => false,
                'expectedIsSingleUrl' => true,
            ],
        ];
    }

    /**
     * @dataProvider getOwnersDataProvider
     *
     * @param array $remoteTestData
     * @param string[] $expectedOwners
     */
    public function testGetOwners(array $remoteTestData, array $expectedOwners)
    {
        $remoteTest = new RemoteTest($remoteTestData);

        $owners = $remoteTest->getOwners();

        $this->assertEquals($expectedOwners, $owners->toArray());
    }

    /**
     * @return array
     */
    public function getOwnersDataProvider()
    {
        return [
            'no remote test data' => [
                'remoteTestData' => [],
                'expectedOwners' => [],
            ],
            'single owner' => [
                'remoteTestData' => [
                    'owners' => [
                        'user@example.com',
                    ],
                ],
                'expectedOwners' => [
                    'user@example.com',
                ],
            ],
            'multiple owners' => [
                'remoteTestData' => [
                    'owners' => [
                        'user1@example.com',
                        'user2@example.com',
                    ],
                ],
                'expectedOwners' => [
                    'user1@example.com',
                    'user2@example.com',
                ],
            ],
        ];
    }
}
