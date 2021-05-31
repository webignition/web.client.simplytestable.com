<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model\Task;

use App\Entity\Task\Task;
use App\Model\Task\Collection;
use App\Tests\Factory\ModelFactory;

class CollectionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider getHashDataProvider
     */
    public function testGetHash(array $taskValuesCollection, string $expectedHash)
    {
        $tasks = [];

        foreach ($taskValuesCollection as $taskValues) {
            $tasks[] = ModelFactory::createTask($taskValues);
        }

        $collection = new Collection($tasks);

        $hash = $collection->getHash();
        $this->assertEquals($expectedHash, $hash);
    }

    public function getHashDataProvider(): array
    {
        return [
            'empty' => [
                'taskValuesCollection' => [],
                'expectedHash' => 'd41d8cd98f00b204e9800998ecf8427e',
            ],
            'single task, id=1, state=completed' => [
                'taskValuesCollection' => [
                    [
                        ModelFactory::TASK_TASK_ID => 1,
                        ModelFactory::TASK_STATE => Task::STATE_COMPLETED,
                    ],
                ],
                'expectedHash' => 'b0d4cbce4ef5dea60d9a0902d69eaa28',
            ],
            'single task, id=2, state=cancelled' => [
                'taskValuesCollection' => [
                    [
                        ModelFactory::TASK_TASK_ID => 2,
                        ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                    ],
                ],
                'expectedHash' => '8c8b0aedced3b4ae103f63ce9e0f114b',
            ],
            'two tasks' => [
                'taskValuesCollection' => [
                    [
                        ModelFactory::TASK_TASK_ID => 1,
                        ModelFactory::TASK_STATE => Task::STATE_COMPLETED,
                    ],
                    [
                        ModelFactory::TASK_TASK_ID => 2,
                        ModelFactory::TASK_STATE => Task::STATE_CANCELLED,
                    ],
                ],
                'expectedHash' => '967fc2198013bb0e68000288b63adb64',
            ],
        ];
    }
}
