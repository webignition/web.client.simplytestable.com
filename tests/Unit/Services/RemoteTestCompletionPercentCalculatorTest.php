<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Test as TestEntity;
use App\Model\RemoteTest\RemoteTest;
use App\Services\RemoteTestCompletionPercentCalculator;

class RemoteTestCompletionPercentCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var RemoteTestCompletionPercentCalculator
     */
    private $calculator;

    protected function setUp()
    {
        parent::setUp();

        $this->calculator = new RemoteTestCompletionPercentCalculator();
    }

    /**
     * @dataProvider calculateForCrawlDataProvider
     */
    public function testCalculateForCrawl(array $crawlData, int $expectedCompletionPercent)
    {
        $remoteTest = new RemoteTest([
            'state' => TestEntity::STATE_CRAWLING,
            'crawl' => $crawlData,
        ]);

        $completionPercent = $this->calculator->calculate(
            TestEntity::STATE_CRAWLING,
            $remoteTest->getTaskCount(),
            [],
            $crawlData
        );

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    public function calculateForCrawlDataProvider(): array
    {
        return [
            'crawling, no crawl data, no tasks' => [
                'crawlData' => [],
                'expectedCompletionPercent' => 0,
            ],
            'crawling, has crawl data, no discovered urls' => [
                'crawlData' => [
                    'discovered_url_count' => 0,
                ],
                'expectedCompletionPercent' => 0,
            ],
            'crawling, has crawl data, has discovered urls, 10% complete' => [
                'crawlData' => [
                    'discovered_url_count' => 10,
                    'limit' => 100,
                ],
                'expectedCompletionPercent' => 10,
            ],
            'crawling, has crawl data, has discovered urls, 80% complete (actual: 80%)' => [
                'crawlData' => [
                    'discovered_url_count' => 800,
                    'limit' => 1000,
                ],
                'expectedCompletionPercent' => 80,
            ],
            'crawling, has crawl data, has discovered urls, 80% complete (actual: 80.1%)' => [
                'crawlData' => [
                    'discovered_url_count' => 801,
                    'limit' => 1000,
                ],
                'expectedCompletionPercent' => 80,
            ],
            'crawling, has crawl data, has discovered urls, 81% complete (actual: 80.5%)' => [
                'crawlData' => [
                    'discovered_url_count' => 805,
                    'limit' => 1000,
                ],
                'expectedCompletionPercent' => 81,
            ],
        ];
    }

    /**
     * @dataProvider calculateWithAllTasksFinishedDataProvider
     */
    public function testCalculateWithAllTasksFinished(array $taskCountByState, int $expectedCompletionPercent)
    {
        $remoteTest = new RemoteTest([
            'task_count' => 10,
            'task_count_by_state' => $taskCountByState,
        ]);

        $completionPercent = $this->calculator->calculate(
            TestEntity::STATE_COMPLETED,
            $remoteTest->getTaskCount(),
            $remoteTest->getTaskCountByState(),
            $remoteTest->getCrawl()
        );

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    public function calculateWithAllTasksFinishedDataProvider(): array
    {
        return [
            'all cancelled' => [
                'taskCountByState' => [
                    'cancelled' => 10,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'awaiting-cancellation' => 0,
                    'queued-for-assignment' => 0,
                    'failed-no-retry-available' => 0,
                    'failed-retry-available' => 0,
                    'failed-retry-limit-reached' => 0,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all completed' => [
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 10,
                    'awaiting-cancellation' => 0,
                    'queued-for-assignment' => 0,
                    'failed-no-retry-available' => 0,
                    'failed-retry-available' => 0,
                    'failed-retry-limit-reached' => 0,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all failed' => [
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'awaiting-cancellation' => 0,
                    'queued-for-assignment' => 0,
                    'failed-no-retry-available' => 3,
                    'failed-retry-available' => 3,
                    'failed-retry-limit-reached' => 4,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all skipped' => [
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'awaiting-cancellation' => 0,
                    'queued-for-assignment' => 0,
                    'failed-no-retry-available' => 0,
                    'failed-retry-available' => 0,
                    'failed-retry-limit-reached' => 0,
                    'skipped' => 10,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'mixture of finished states' => [
                'taskCountByState' => [
                    'cancelled' => 2,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 2,
                    'awaiting-cancellation' => 2,
                    'queued-for-assignment' => 0,
                    'failed-no-retry-available' => 1,
                    'failed-retry-available' => 1,
                    'failed-retry-limit-reached' => 1,
                    'skipped' => 1,
                ],
                'expectedCompletionPercent' => 100,
            ],
        ];
    }

    /**
     * @dataProvider calculateCompletionPercentDataProvider
     */
    public function testCalculateCompletionPercent(RemoteTest $remoteTest, int $expectedCompletionPercent)
    {
        $completionPercent = $this->calculator->calculate(
            $remoteTest->getState(),
            $remoteTest->getTaskCount(),
            $remoteTest->getTaskCountByState(),
            $remoteTest->getCrawl()
        );

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    public function calculateCompletionPercentDataProvider(): array
    {
        return [
            'crawling, no crawl data, no tasks' => [
                'remoteTest' => new RemoteTest([
                    'state' => TestEntity::STATE_CRAWLING,
                    'crawl' => [],
                    'task_count' => 0,
                ]),
                'expectedCompletionPercent' => 0,
            ],
            'not crawling, no tasks' => [
                'remoteTest' => new RemoteTest([
                    'state' => TestEntity::STATE_QUEUED,
                    'task_count' => 0,
                ]),
                'expectedCompletionPercent' => 0,
            ],
            'partially complete, 10%' => [
                'remoteTest' => new RemoteTest([
                    'state' => TestEntity::STATE_IN_PROGRESS,
                    'task_count' => 100,
                    'task_count_by_state' => [
                        'cancelled' => 10,
                        'queued' => 0,
                        'in-progress' => 0,
                        'completed' => 0,
                        'awaiting-cancellation' => 0,
                        'queued-for-assignment' => 0,
                        'failed-no-retry-available' => 0,
                        'failed-retry-available' => 0,
                        'failed-retry-limit-reached' => 0,
                        'skipped' => 0,
                    ],
                ]),
                'expectedCompletionPercent' => 10,
            ],
            'partially complete, 80%' => [
                'remoteTest' => new RemoteTest([
                    'state' => TestEntity::STATE_IN_PROGRESS,
                    'task_count' => 100,
                    'task_count_by_state' => [
                        'cancelled' => 10,
                        'queued' => 0,
                        'in-progress' => 0,
                        'completed' => 60,
                        'awaiting-cancellation' => 0,
                        'queued-for-assignment' => 0,
                        'failed-no-retry-available' => 0,
                        'failed-retry-available' => 0,
                        'failed-retry-limit-reached' => 0,
                        'skipped' => 10,
                    ],
                ]),
                'expectedCompletionPercent' => 80,
            ],
        ];
    }
}
