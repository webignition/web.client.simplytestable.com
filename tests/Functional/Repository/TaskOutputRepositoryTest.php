<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Output;
use App\Repository\TaskOutputRepository;
use App\Tests\Factory\OutputFactory;
use App\Tests\Functional\AbstractBaseTestCase;

class TaskOutputRepositoryTest extends AbstractBaseTestCase
{
    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        /* @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get(EntityManagerInterface::class);

        $this->taskOutputRepository = $entityManager->getRepository(Output::class);
    }

    /**
     * @dataProvider findHashlessOutputIdsDataProvider
     */
    public function testFindHashlessOutputIds(array $outputValuesCollection, ?int $limit, array $expectedOutputIndices)
    {
        $outputs = $this->createOutputCollection($outputValuesCollection);
        $outputIds = [];

        foreach ($outputs as $output) {
            $outputIds[] = $output->getId();
        }

        $expectedOutputIds = [];
        foreach ($outputIds as $outputIndex => $outputId) {
            if (in_array($outputIndex, $expectedOutputIndices)) {
                $expectedOutputIds[] = $outputId;
            }
        }

        $hashlessOutputIds = $this->taskOutputRepository->findHashlessOutputIds($limit);

        $this->assertEquals($expectedOutputIds, $hashlessOutputIds);
    }

    public function findHashlessOutputIdsDataProvider(): array
    {
        return [
            'no outputs' => [
                'outputValuesCollection' => [],
                'limit' => null,
                'expectedOutputIndices' => [],
            ],
            'no hashless outputs' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => 10,
                'expectedOutputIndices' => [],
            ],
            'some hashless outputs' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => 10,
                'expectedOutputIndices' => [0, 2],
            ],
            'all hashless outputs; limit null' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => null,
                'expectedOutputIndices' => [0, 1, 2],
            ],
            'all hashless outputs; limit zero' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => 0,
                'expectedOutputIndices' => [0, 1, 2],
            ],
            'all hashless outputs; limit one' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => 1,
                'expectedOutputIndices' => [0],
            ],
            'all hashless outputs; limit two' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                    ],
                ],
                'limit' => 2,
                'expectedOutputIndices' => [0, 1],
            ],
        ];
    }

    /**
     * @dataProvider findDuplicateHashesDataProvider
     */
    public function testFindDuplicateHashes(array $outputValuesCollection, ?int $limit, array $expectedDuplicateHashes)
    {
        $this->createOutputCollection($outputValuesCollection);

        $duplicateHashes = $this->taskOutputRepository->findDuplicateHashes($limit);

        $this->assertEquals($expectedDuplicateHashes, $duplicateHashes);
    }

    public function findDuplicateHashesDataProvider(): array
    {
        return [
            'no outputs' => [
                'outputValuesCollection' => [],
                'limit' => null,
                'expectedOutputIndices' => [],
            ],
            'no duplicate hashes' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => 10,
                'expectedDuplicateHashes' => [],
            ],
            'one duplicate hash' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 1,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foo',
                    ],
                ],
                'limit' => 10,
                'expectedDuplicateHashes' => ['foo'],
            ],
            'some duplicate hashes' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
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
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                ],
                'limit' => 10,
                'expectedDuplicateHashes' => ['foo', 'foobar'],
            ],
            'some duplicate hashes; limit null' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
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
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                ],
                'limit' => null,
                'expectedDuplicateHashes' => ['foo', 'foobar'],
            ],
            'some duplicate hashes; limit one' => [
                'outputValuesCollection' => [
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 0,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'bar',
                    ],
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
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                    [
                        OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                        OutputFactory::KEY_ERROR_COUNT => 2,
                        OutputFactory::KEY_WARNING_COUNT => 0,
                        OutputFactory::KEY_HASH => 'foobar',
                    ],
                ],
                'limit' => 1,
                'expectedDuplicateHashes' => ['foo'],
            ],
        ];
    }

    /**
     * @dataProvider findIdsNotInDataProvider
     */
    public function testFindIdsNotIn(array $excludeIndices, array $expectedOutputIndices)
    {
        $outputs = $this->createOutputCollection([
            [
                OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                OutputFactory::KEY_ERROR_COUNT => 0,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                OutputFactory::KEY_ERROR_COUNT => 1,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
            [
                OutputFactory::KEY_TYPE => Output::TYPE_HTML_VALIDATION,
                OutputFactory::KEY_ERROR_COUNT => 2,
                OutputFactory::KEY_WARNING_COUNT => 0,
            ],
        ]);

        $outputIds = [];

        foreach ($outputs as $output) {
            $outputIds[] = $output->getId();
        }

        $excludeIds = [];
        foreach ($outputIds as $outputIndex => $outputId) {
            if (in_array($outputIndex, $excludeIndices)) {
                $excludeIds[] = $outputId;
            }
        }

        $expectedOutputIds = [];
        foreach ($outputIds as $outputIndex => $outputId) {
            if (in_array($outputIndex, $expectedOutputIndices)) {
                $expectedOutputIds[] = $outputId;
            }
        }

        $hashlessOutputIds = $this->taskOutputRepository->findIdsNotIn($excludeIds);

        $this->assertEquals($expectedOutputIds, $hashlessOutputIds);
    }

    public function findIdsNotInDataProvider(): array
    {
        return [
            'no exclusions' => [
                'excludeIndices' => [],
                'expectedOutputIndices' => [0, 1, 2],
            ],
            'exclude 0' => [
                'excludeIndices' => [0],
                'expectedOutputIndices' => [1, 2],
            ],
            'exclude 1' => [
                'excludeIndices' => [1],
                'expectedOutputIndices' => [0, 2],
            ],
            'exclude 0, 1' => [
                'excludeIndices' => [0, 1],
                'expectedOutputIndices' => [2],
            ],
        ];
    }

    /**
     * @param array $outputValuesCollection
     *
     * @return Output[]
     */
    private function createOutputCollection(array $outputValuesCollection): array
    {
        $outputs = [];

        if (!empty($outputValuesCollection)) {
            $outputFactory = new OutputFactory(self::$container);

            foreach ($outputValuesCollection as $outputValues) {
                $outputs[] = $outputFactory->create($outputValues);
            }
        }

        return $outputs;
    }
}
