<?php

namespace App\Tests\Functional\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use App\Command\Migrate\CanonicaliseTaskOutputCommand;
use App\Entity\Task\Output;
use App\Tests\Factory\OutputFactory;
use App\Tests\Factory\TaskFactory;
use App\Tests\Factory\TestFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class MigrateCanonicaliseTaskOutputCommandTest extends AbstractBaseTestCase
{
    /**
     * @var CanonicaliseTaskOutputCommand
     */
    protected $canonicaliseTaskOutputCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->canonicaliseTaskOutputCommand = self::$container->get(CanonicaliseTaskOutputCommand::class);
    }

    /**
     * @dataProvider runDataProvider
     *
     * @param array $taskValuesCollection
     * @param array $outputValuesCollection
     * @param int|null $limit
     * @param int|bool $dryRun
     * @param array $expectedOutputHashes
     *
     * @throws \Exception
     */
    public function testRun(
        array $taskValuesCollection,
        array $outputValuesCollection,
        $limit,
        $dryRun,
        array $expectedOutputHashes
    ) {
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $taskOutputRepository = $entityManager->getRepository(Output::class);

        $taskOutputs = [];

        if (!empty($outputValuesCollection)) {
            $outputFactory = new OutputFactory(self::$container);

            foreach ($outputValuesCollection as $outputValues) {
                $taskOutputs[] = $outputFactory->create($outputValues);
            }
        }

        if (!empty($taskValuesCollection)) {
            $testFactory = new TestFactory(self::$container);
            $test = $testFactory->create([
                TestFactory::KEY_TEST_ID => 1,
            ]);

            $taskFactory = new TaskFactory(self::$container);

            foreach ($taskValuesCollection as $taskValues) {
                $taskValues[TaskFactory::KEY_TEST] = $test;

                if (isset($taskValues['output-index'])) {
                    $taskValues[TaskFactory::KEY_OUTPUT] = $taskOutputs[$taskValues['output-index']];
                }

                $taskFactory->create($taskValues);
            }
        }

        $args = [];

        if (!is_null($limit)) {
            $args['--' . CanonicaliseTaskOutputCommand::OPT_LIMIT] = $limit;
        }

        if ($dryRun) {
            $args['--' . CanonicaliseTaskOutputCommand::OPT_DRY_RUN] = true;
        }

        $returnValue = $this->canonicaliseTaskOutputCommand->run(new ArrayInput($args), new NullOutput());

        $this->assertEquals(0, $returnValue);

        /* @var Output[] $outputs */
        $outputs = $taskOutputRepository->findAll();

        $this->assertCount(count($expectedOutputHashes), $outputs);

        foreach ($outputs as $index => $output) {
            $expectedOutputHash = $expectedOutputHashes[$index];
            $this->assertEquals($expectedOutputHash, $output->getHash());
        }
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            'no task outputs, no limit, no dry-run' => [
                'taskValuesCollection' => [],
                'outputValuesCollection' => [],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [],
            ],
            'has output, no duplicate hashes, no limit, no dry-run' => [
                'taskValuesCollection' => [],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    'foo',
                ],
            ],
            'has output, has duplicate hashes, no tasks using output, no limit, no dry-run' => [
                'taskValuesCollection' => [],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    'foo',
                    'foo',
                ],
            ],
            'has output, has duplicate hashes, has tasks using output, no limit, no dry-run' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        'output-index' => 0,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        'output-index' => 1,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        'output-index' => 2,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        'output-index' => 3,
                    ],
                ],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_CSS_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_CSS_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
                ],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    'foo',
                    'bar',
                ],
            ],
            'has output, has duplicate hashes, has tasks using output, limit=1, no dry-run' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        'output-index' => 0,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        'output-index' => 1,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 3,
                        'output-index' => 2,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 4,
                        'output-index' => 3,
                    ],
                ],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_CSS_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_CSS_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
                ],
                'limit' => 1,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    'foo',
                    'foo',
                    'bar',
                ],
            ],
            'has output, has duplicate hashes, has tasks using output no limit, dry-run' => [
                'taskValuesCollection' => [
                    [
                        TaskFactory::KEY_TASK_ID => 1,
                        'output-index' => 0,
                    ],
                    [
                        TaskFactory::KEY_TASK_ID => 2,
                        'output-index' => 1,
                    ],
                ],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => null,
                'dryRun' => true,
                'expectedOutputHashes' => [
                    'foo',
                    'foo',
                ],
            ],
        ];
    }
}
