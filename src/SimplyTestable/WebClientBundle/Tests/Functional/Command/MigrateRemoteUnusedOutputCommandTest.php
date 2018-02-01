<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Command;

use SimplyTestable\WebClientBundle\Command\MigrateRemoveUnusedOutputCommand;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Tests\Factory\OutputFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TaskFactory;
use SimplyTestable\WebClientBundle\Tests\Factory\TestFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class MigrateRemoteUnusedOutputCommandTest extends AbstractBaseTestCase
{
    /**
     * @var MigrateRemoveUnusedOutputCommand
     */
    protected $migrateRemoveUnusedOutputCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->migrateRemoveUnusedOutputCommand = $this->container->get(MigrateRemoveUnusedOutputCommand::class);
    }

    /**
     * @dataProvider runDataProvider
     *
     * @param array $taskValuesCollection
     * @param array $outputValuesCollection
     * @param int|bool $dryRun
     * @param int[] $expectedOutputIndices
     *
     * @throws \Exception
     */
    public function testRun(
        array $taskValuesCollection,
        array $outputValuesCollection,
        $dryRun,
        array $expectedOutputIndices
    ) {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');
        $taskOutputRepository = $entityManager->getRepository(Output::class);

        /* @var Output[] $taskOutputs */
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

        if ($dryRun) {
            $args['--' . MigrateRemoveUnusedOutputCommand::OPT_DRY_RUN] = true;
        }

        $returnValue = $this->migrateRemoveUnusedOutputCommand->run(new ArrayInput($args), new NullOutput());

        $this->assertEquals(0, $returnValue);

        $expectedOutputIds = [];
        foreach ($taskOutputs as $taskOutputIndex => $taskOutput) {
            if (in_array($taskOutputIndex, $expectedOutputIndices)) {
                $expectedOutputIds[] = $taskOutput->getId();
            }
        }

        /* @var Output[] $outputs */
        $outputs = $taskOutputRepository->findAll();

        $outputIds = [];

        foreach ($outputs as $index => $output) {
            $outputIds[] = $output->getId();
        }

        $this->assertEquals($expectedOutputIds, $outputIds);
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            'no task outputs' => [
                'taskValuesCollection' => [],
                'outputValuesCollection' => [],
                'dryRun' => null,
                'expectedOutputIndices' => [],
            ],
            'has output, none used, no dry-run' => [
                'taskValuesCollection' => [],
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'dryRun' => null,
                'expectedOutputIndices' => [0],
            ],
            'has output, all used, no dry-run' => [
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
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'dryRun' => null,
                'expectedOutputIndices' => [0, 1],
            ],
            'has output, some used, no dry-run' => [
                'taskValuesCollection' => [
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
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'dryRun' => null,
                'expectedOutputIndices' => [1],
            ],
            'has output, some used, dry-run' => [
                'taskValuesCollection' => [
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
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'dryRun' => true,
                'expectedOutputIndices' => [0, 1],
            ],
        ];
    }
}
