<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Tests\Factory\OutputFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

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
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $this->taskOutputRepository = $entityManager->getRepository(Output::class);
    }

    /**
     * @dataProvider findHashlessOutputIdsDataProvider
     *
     * @param array $outputValuesCollection
     * @param int|null $limit
     * @param int[] $expectedOutputIndices
     */
    public function testFindHashlessOutputIds(array $outputValuesCollection, $limit, array $expectedOutputIndices)
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

    /**
     * @return array
     */
    public function findHashlessOutputIdsDataProvider()
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
     *
     * @param array $outputValuesCollection
     * @param int|null $limit
     * @param array $expectedDuplicateHashes
     */
    public function testFindDuplicateHashes(array $outputValuesCollection, $limit, array $expectedDuplicateHashes)
    {
        $this->createOutputCollection($outputValuesCollection);

        $duplicateHashes = $this->taskOutputRepository->findDuplicateHashes($limit);

        $this->assertEquals($expectedDuplicateHashes, $duplicateHashes);
    }

    /**
     * @return array
     */
    public function findDuplicateHashesDataProvider()
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
     *
     * @param int[] $excludeIndices
     * @param int[] $expectedOutputIndices
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

    /**
     * @return array
     */
    public function findIdsNotInDataProvider()
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
    private function createOutputCollection(array $outputValuesCollection)
    {
        $outputs = [];

        if (!empty($outputValuesCollection)) {
            $outputFactory = new OutputFactory($this->container);

            foreach ($outputValuesCollection as $outputValues) {
                $outputs[] = $outputFactory->create($outputValues);
            }
        }

        return $outputs;
    }
}
