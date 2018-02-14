<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Services\Factory\TaskOutputFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\MockFactory;

class TaskOutputFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param TaskOutputRepository $taskOutputRepository
     * @param string $type
     * @param array $outputData
     * @param array $expectedOutputData
     */
    public function testCreate(
        TaskOutputRepository $taskOutputRepository,
        $type,
        array $outputData,
        array $expectedOutputData
    ) {
        /* @var EntityManagerInterface|MockInterface $entityManager */
        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getRepository')
            ->with(Output::class)
            ->andReturn($taskOutputRepository);

        $taskOutputFactory = new TaskOutputFactory($entityManager);

        $output = $taskOutputFactory->create($type, $outputData);

        $this->assertInstanceOf(Output::class, $output);

        $this->assertEquals($expectedOutputData['type'], $output->getType());
        $this->assertEquals($expectedOutputData['content'], $output->getContent());
        $this->assertEquals($expectedOutputData['error_count'], $output->getErrorCount());
        $this->assertEquals($expectedOutputData['warning_count'], $output->getWarningCount());
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        $existingOutput = new Output();
        $existingOutput->setType(Task::TYPE_CSS_VALIDATION);
        $existingOutput->setContent('foo');
        $existingOutput->setErrorCount(1);
        $existingOutput->setWarningCount(2);
        $existingOutput->generateHash();

        return [
            'no existing output' => [
                'taskOutputRepository' => MockFactory::createTaskOutputRepository([
                    'findOneBy' => [
                        'with' => ['hash' => '792fa82919408a2df98c12743a7ed366'],
                        'return' => null,
                    ],
                ]),
                'type' => Task::TYPE_HTML_VALIDATION,
                'outputData' => [
                    'output' => '{"messages":[]}',
                    'error_count' => 0,
                    'warning_count' => 0,
                ],
                'expectedOutputData' => [
                    'content' => '{"messages":[]}',
                    'type' => Task::TYPE_HTML_VALIDATION,
                    'error_count' => 0,
                    'warning_count' => 0,
                ],
            ],
            'has existing output' => [
                'taskOutputRepository' => MockFactory::createTaskOutputRepository([
                    'findOneBy' => [
                        'with' => ['hash' => $existingOutput->getHash()],
                        'return' => $existingOutput,
                    ],
                ]),
                'type' => Task::TYPE_CSS_VALIDATION,
                'outputData' => [
                    'output' => 'foo',
                    'error_count' => 1,
                    'warning_count' => 2,
                ],
                'expectedOutputData' => [
                    'content' => $existingOutput->getContent(),
                    'type' => $existingOutput->getType(),
                    'error_count' => $existingOutput->getErrorCount(),
                    'warning_count' => $existingOutput->getWarningCount(),
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
