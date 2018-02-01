<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command;

use SimplyTestable\WebClientBundle\Command\MigrateCanonicaliseTaskOutputCommand;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Tests\Factory\OutputFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class MigrateCanonicaliseTaskOutputCommandTest extends AbstractBaseTestCase
{
    /**
     * @var MigrateCanonicaliseTaskOutputCommand
     */
    protected $migrateCanonicaliseTaskOutputCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->migrateCanonicaliseTaskOutputCommand = $this->container->get(
            MigrateCanonicaliseTaskOutputCommand::class
        );
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
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $taskOutputRepository = $entityManager->getRepository(Output::class);

        $taskOutputs = [];

        if (!empty($outputValuesCollection)) {
            $outputFactory = new OutputFactory($this->container);

            foreach ($outputValuesCollection as $outputValues) {
                $taskOutputs[] = $outputFactory->create($outputValues);
            }
        }

        if (!empty($taskValuesCollection)) {
            $testFactory = new TestFactory($this->container);
            $test = $testFactory->create([
                TestFactory::KEY_TEST_ID => 1,
            ]);

            $taskFactory = new TaskFactory($this->container);

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
            $args['--' . MigrateCanonicaliseTaskOutputCommand::OPT_LIMIT] = $limit;
        }

        if ($dryRun) {
            $args['--' . MigrateCanonicaliseTaskOutputCommand::OPT_DRY_RUN] = true;
        }

        $returnValue = $this->migrateCanonicaliseTaskOutputCommand->run(new ArrayInput($args), new NullOutput());

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
