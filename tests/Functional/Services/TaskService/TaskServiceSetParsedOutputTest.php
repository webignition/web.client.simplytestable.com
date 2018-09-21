<?php

namespace App\Tests\Functional\Services\TaskService;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Model\TaskOutput\Result;
use App\Tests\Factory\ModelFactory;

class TaskServiceSetParsedOutputTest extends AbstractTaskServiceTest
{
    /**
     * @dataProvider setParsedOutputDataProvider
     *
     * @param Task $task
     * @param bool $expectedHasOutput
     */
    public function testSetParsedOutput(Task $task, $expectedHasOutput)
    {
        if ($expectedHasOutput) {
            $this->assertNull($task->getOutput()->getResult());
        }

        $this->taskService->setParsedOutput($task);

        $this->assertEquals($expectedHasOutput, $task->hasOutput());

        if ($expectedHasOutput) {
            $this->assertInstanceOf(Result::class, $task->getOutput()->getResult());
        }
    }

    /**
     * @return array
     */
    public function setParsedOutputDataProvider()
    {
        return [
            'no output' => [
                'task' => ModelFactory::createTask(),
                'expectedHasOutput' => false,
            ],
            'html validation output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                        ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_HTML_VALIDATION,
                        ModelFactory::TASK_OUTPUT_CONTENT => '{"messages":[]}',
                    ]),
                ]),
                'expectedHasOutput' => true,
            ],
            'css validation output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                        ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_CSS_VALIDATION,
                        ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                    ]),
                ]),
                'expectedHasOutput' => true,
            ],
            'link integrity output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                        ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_LINK_INTEGRITY,
                        ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                    ]),
                ]),
                'expectedHasOutput' => true,
            ],
        ];
    }
}
