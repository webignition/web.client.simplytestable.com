<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Entity\Test as TestEntity;
use App\Services\TestCompletionPercentCalculator;

class TestCompletionPercentCalculatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestCompletionPercentCalculator
     */
    private $calculator;

    protected function setUp()
    {
        parent::setUp();

        $this->calculator = new TestCompletionPercentCalculator();
    }

    /**
     * @dataProvider calculateForCrawlDataProvider
     */
    public function testCalculateForCrawl(array $crawlData, int $expectedCompletionPercent)
    {
        $completionPercent = $this->calculator->calculate(
            TestEntity::STATE_CRAWLING,
            0,
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
    public function testCalculateWithAllTasksFinished(
        int $taskCount,
        array $taskCountByState,
        int $expectedCompletionPercent
    ) {
        $completionPercent = $this->calculator->calculate(
            TestEntity::STATE_COMPLETED,
            $taskCount,
            $taskCountByState,
            []
        );

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    public function calculateWithAllTasksFinishedDataProvider(): array
    {
        return [
            'all cancelled' => [
                'taskCount' => 10,
                'taskCountByState' => [
                    'cancelled' => 10,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'failed' => 0,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all completed' => [
                'taskCount' => 10,
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 10,
                    'failed' => 0,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all failed' => [
                'taskCount' => 10,
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'failed' => 10,
                    'skipped' => 0,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'all skipped' => [
                'taskCount' => 10,
                'taskCountByState' => [
                    'cancelled' => 0,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'failed' => 0,
                    'skipped' => 10,
                ],
                'expectedCompletionPercent' => 100,
            ],
            'mixture of finished states' => [
                'taskCount' => 10,
                'taskCountByState' => [
                    'cancelled' => 4,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 2,
                    'failed' => 3,
                    'skipped' => 1,
                ],
                'expectedCompletionPercent' => 100,
            ],
        ];
    }

    /**
     * @dataProvider calculateCompletionPercentDataProvider
     */
    public function testCalculateCompletionPercent(
        string $state,
        int $taskCount,
        array $taskCountByState,
        array $crawlData,
        int $expectedCompletionPercent
    ) {
        $completionPercent = $this->calculator->calculate(
            $state,
            $taskCount,
            $taskCountByState,
            $crawlData
        );

        $this->assertEquals($expectedCompletionPercent, $completionPercent);
    }

    public function calculateCompletionPercentDataProvider(): array
    {
        return [
            'crawling, no crawl data, no tasks' => [
                'state' => TestEntity::STATE_CRAWLING,
                'taskCount' => 0,
                'taskCountByState' => [],
                'crawlData' => [],
                'expectedCompletionPercent' => 0,
            ],
            'not crawling, no tasks' => [
                'state' => TestEntity::STATE_QUEUED,
                'taskCount' => 0,
                'taskCountByState' => [],
                'crawlData' => [],
                'expectedCompletionPercent' => 0,
            ],
            'partially complete, 10%' => [
                'state' => TestEntity::STATE_IN_PROGRESS,
                'taskCount' => 100,
                'taskCountByState' => [
                    'cancelled' => 10,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 0,
                    'failed' => 0,
                    'skipped' => 0,
                ],
                'crawlData' => [],
                'expectedCompletionPercent' => 10,
            ],
            'partially complete, 80%' => [
                'state' => TestEntity::STATE_IN_PROGRESS,
                'taskCount' => 100,
                'taskCountByState' => [
                    'cancelled' => 10,
                    'queued' => 0,
                    'in-progress' => 0,
                    'completed' => 60,
                    'failed' => 0,
                    'skipped' => 10,
                ],
                'crawlData' => [],
                'expectedCompletionPercent' => 80,
            ],
        ];
    }
}
