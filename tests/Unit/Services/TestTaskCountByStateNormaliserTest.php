<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use App\Services\TestTaskCountByStateNormaliser;

class TestTaskCountByStateNormaliserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TestTaskCountByStateNormaliser
     */
    private $normaliser;

    protected function setUp()
    {
        parent::setUp();

        $this->normaliser = new TestTaskCountByStateNormaliser();
    }

    /**
     * @dataProvider calculateTaskCountByStateDataProvider
     */
    public function testCalculateTaskCountByState(array $taskCountByState, array $expectedTaskCountByState)
    {
        $normalisedTaskCountByState = $this->normaliser->normalise($taskCountByState);

        $this->assertEquals($expectedTaskCountByState, $normalisedTaskCountByState);
    }

    public function calculateTaskCountByStateDataProvider(): array
    {
        return [
            'empty source' => [
                'taskCountByState' => [],
                'expectedTaskCountByState' => [
                    'in_progress' => 0,
                    'queued' => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                    'failed' => 0,
                    'skipped' => 0,
                ],
            ],
            'mixed source' => [
                'taskCountByState' => [
                    'cancelled' => 1,
                    'in-progress' => 2,
                    'queued' => 4,
                    'completed' => 8,
                    'skipped' => 16,
                    'queued-for-assignment' => 32,
                    'awaiting-cancellation' => 64,
                    'failed-no-retry-available' => 128,
                    'failed-retry-available' => 256,
                    'failed-retry-limit-reached' => 512,
                ],
                'expectedTaskCountByState' => [
                    'cancelled' => 65,
                    'in_progress' => 2,
                    'queued' => 36,
                    'completed' => 8,
                    'skipped' => 16,
                    'failed' => 896,
                ],
            ],
        ];
    }
}
