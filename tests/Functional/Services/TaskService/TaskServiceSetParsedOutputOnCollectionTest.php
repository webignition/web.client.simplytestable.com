<?php /** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Output;
use App\Model\TaskOutput\Result;
use App\Tests\Factory\ModelFactory;

class TaskServiceSetParsedOutputOnCollectionTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider setParsedOutputOnCollectionDataProvider
     */
    public function testSetParsedOutputOnCollection(array $tasks, array $expectedHasOutput)
    {
        foreach ($tasks as $taskIndex => $task) {
            $expectedTaskHasOutput = $expectedHasOutput[$taskIndex];

            if ($expectedTaskHasOutput) {
                $this->assertNull($task->getOutput()->getResult());
            }
        }

        $this->taskService->setParsedOutputOnCollection($tasks);

        foreach ($tasks as $taskIndex => $task) {
            $expectedTaskHasOutput = $expectedHasOutput[$taskIndex];

            $this->assertEquals($expectedTaskHasOutput, $task->hasOutput());

            if ($expectedTaskHasOutput) {
                $this->assertInstanceOf(Result::class, $task->getOutput()->getResult());
            }
        }
    }

    public function setParsedOutputOnCollectionDataProvider(): array
    {
        return [
            'no output' => [
                'tasks' => [
                    ModelFactory::createTask(),
                ],
                'expectedHasOutput' => [
                    false,
                ],
            ],
            'single html validation output' => [
                'tasks' => [
                    ModelFactory::createTask([
                        ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                            ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT_CONTENT => '{"messages":[]}',
                        ]),
                    ]),
                ],
                'expectedHasOutput' => [
                    true,
                ],
            ],
            'single css validation output' => [
                'tasks' => [
                    ModelFactory::createTask([
                        ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                            ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_CSS_VALIDATION,
                            ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                        ]),
                    ]),
                ],
                'expectedHasOutput' => [
                    true,
                ],
            ],
            'single link integrity output' => [
                'tasks' => [
                    ModelFactory::createTask([
                        ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                            ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_LINK_INTEGRITY,
                            ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                        ]),
                    ]),
                ],
                'expectedHasOutput' => [
                    true,
                ],
            ],
            'mixed' => [
                'tasks' => [
                    ModelFactory::createTask(),
                    ModelFactory::createTask([
                        ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                            ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_HTML_VALIDATION,
                            ModelFactory::TASK_OUTPUT_CONTENT => '{"messages":[]}',
                        ]),
                    ]),
                    ModelFactory::createTask([
                        ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                            ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_CSS_VALIDATION,
                            ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                        ]),
                    ]),
                ],
                'expectedHasOutput' => [
                    false,
                    true,
                    true,
                    true,
                    true,
                ],
            ],
        ];
    }
}
