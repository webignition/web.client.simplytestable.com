<?php

namespace App\Tests\Functional\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use App\Command\Migrate\AddHashToHashlessOutputCommand;
use App\Entity\Task\Output;
use App\Tests\Factory\OutputFactory;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class AddHashToHashlessOutputCommandTest extends AbstractBaseTestCase
{
    /**
     * @var AddHashToHashlessOutputCommand
     */
    protected $addHashToHashlessOutputCommand;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->addHashToHashlessOutputCommand = self::$container->get(AddHashToHashlessOutputCommand::class);
    }

    /**
     * @dataProvider runDataProvider
     *
     * @param array $outputValuesCollection
     * @param int|null $limit
     * @param int|bool $dryRun
     * @param array $expectedOutputHashes
     *
     * @throws \Exception
     */
    public function testRun(array $outputValuesCollection, $limit, $dryRun, array $expectedOutputHashes)
    {
        $entityManager = self::$container->get(EntityManagerInterface::class);
        $taskOutputRepository = $entityManager->getRepository(Output::class);

        if (!empty($outputValuesCollection)) {
            $outputFactory = new OutputFactory(self::$container);

            foreach ($outputValuesCollection as $outputValues) {
                $outputFactory->create($outputValues);
            }
        }

        $args = [];

        if (!is_null($limit)) {
            $args['--' . AddHashToHashlessOutputCommand::OPT_LIMIT] = $limit;
        }

        if ($dryRun) {
            $args['--' . AddHashToHashlessOutputCommand::OPT_DRY_RUN] = true;
        }

        $returnValue = $this->addHashToHashlessOutputCommand->run(new ArrayInput($args), new NullOutput());

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
                'outputValuesCollection' => [],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [],
            ],
            'single output without hash, no limit, no dry-run' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => null,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    '994c3b7672ad4df0d9af5f925d450b78',
                ],
            ],
            'single output without hash, no limit, dry-run' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => null,
                'dryRun' => true,
                'expectedOutputHashes' => [
                    null,
                ],
            ],
            'single output with hash, no limit, no dry-run' => [
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
            'two outputs without hash, limit=1, no dry-run' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 1,
                    ],
                ],
                'limit' => 1,
                'dryRun' => null,
                'expectedOutputHashes' => [
                    '994c3b7672ad4df0d9af5f925d450b78',
                    null,
                ],
            ],
        ];
    }
}
