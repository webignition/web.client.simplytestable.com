<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskService;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;

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
            'js static analysis output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                        ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_JS_STATIC_ANALYSIS,
                        ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                            '2be27b536970c6988a1f387359237529' => [
                                'statusLine' => '',
                                'entries' => [],
                            ],
                        ]),
                    ]),
                ]),
                'expectedHasOutput' => true,
            ],
            'link integrity output' => [
                'task' => ModelFactory::createTask([
                    ModelFactory::TASK_OUTPUT => ModelFactory::createTaskOutput([
                        ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_JS_STATIC_ANALYSIS,
                        ModelFactory::TASK_OUTPUT_CONTENT => '[]',
                    ]),
                ]),
                'expectedHasOutput' => true,
            ],
        ];
    }
}
