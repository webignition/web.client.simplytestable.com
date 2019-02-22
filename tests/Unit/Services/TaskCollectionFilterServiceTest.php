<?php

namespace App\Tests\Unit\Services;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use App\Entity\Task\Task;
use App\Entity\Test\Test;
use App\Repository\TaskRepository;
use App\Services\TaskCollectionFilterService;

class TaskCollectionFilterServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string[]
     */
    private $excludedStates = [
        Task::STATE_SKIPPED,
        Task::STATE_CANCELLED,
        Task::STATE_IN_PROGRESS,
        Task::STATE_AWAITING_CANCELLATION,
    ];

    /**
     * @dataProvider getRemoteIdCountDataProvider
     *
     * @param EntityManagerInterface $entityManager
     * @param Test $test
     * @param string $typeFilter
     * @param string $outcomeFilter
     */
    public function testGetRemoteIdCount(
        EntityManagerInterface $entityManager,
        Test $test,
        $typeFilter,
        $outcomeFilter
    ) {
        $taskCollectionFilterService = new TaskCollectionFilterService($entityManager);

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setTypeFilter($typeFilter);
        $taskCollectionFilterService->setOutcomeFilter($outcomeFilter);

        $this->assertSame(0, $taskCollectionFilterService->getRemoteIdCount());
    }

    /**
     * @return array
     */
    public function getRemoteIdCountDataProvider()
    {
        $test = new Test();

        return [
            'outcomeFilter: skipped, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        null,
                        [TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED]
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED,
            ],
            'outcomeFilter: skipped, typeFilter: HTML validation' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        Task::TYPE_HTML_VALIDATION,
                        [TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED]
                    )
                ),
                'test' => $test,
                'typeFilter' => Task::TYPE_HTML_VALIDATION,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED,
            ],
            'outcomeFilter: cancelled, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        null,
                        [TaskCollectionFilterService::OUTCOME_FILTER_CANCELLED]
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_CANCELLED,
            ],
            'outcomeFilter: without-errors, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        $this->excludedStates,
                        null,
                        '= 0',
                        'error'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'without-errors',
            ],
            'outcomeFilter: with-errors, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        [],
                        null,
                        '> 0',
                        'error'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'with-errors',
            ],
            'outcomeFilter: with-warnings, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        [],
                        null,
                        '> 0',
                        'warning'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'with-warnings',
            ],
        ];
    }

    /**
     * @dataProvider getRemoteIdsDataProvider
     *
     * @param EntityManagerInterface $entityManager
     * @param Test $test
     * @param $typeFilter
     * @param $outcomeFilter
     */
    public function testGetRemoteIds(
        EntityManagerInterface $entityManager,
        Test $test,
        $typeFilter,
        $outcomeFilter
    ) {
        $taskCollectionFilterService = new TaskCollectionFilterService($entityManager);

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setTypeFilter($typeFilter);
        $taskCollectionFilterService->setOutcomeFilter($outcomeFilter);

        $this->assertSame([], $taskCollectionFilterService->getRemoteIds());
    }

    /**
     * @return array
     */
    public function getRemoteIdsDataProvider()
    {
        $test = new Test();

        return [
            'outcomeFilter: skipped, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        null,
                        [TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED]
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED,
            ],
            'outcomeFilter: skipped, typeFilter: HTML validation' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        Task::TYPE_HTML_VALIDATION,
                        [TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED]
                    )
                ),
                'test' => $test,
                'typeFilter' => Task::TYPE_HTML_VALIDATION,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_SKIPPED,
            ],
            'outcomeFilter: cancelled, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndTaskTypeIncludingStatesTaskRepository(
                        $test,
                        null,
                        [TaskCollectionFilterService::OUTCOME_FILTER_CANCELLED]
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => TaskCollectionFilterService::OUTCOME_FILTER_CANCELLED,
            ],
            'outcomeFilter: without-errors, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        null,
                        '= 0',
                        'error'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'without-errors',
            ],
            'outcomeFilter: with-errors, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        null,
                        '> 0',
                        'error'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'with-errors',
            ],
            'outcomeFilter: with-warnings, typeFilter: null' => [
                'entityManager' => $this->createEntityManager(
                    $this->createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
                        $test,
                        null,
                        '> 0',
                        'warning'
                    )
                ),
                'test' => $test,
                'typeFilter' => null,
                'outcomeFilter' => 'with-warnings',
            ],
        ];
    }

    public function testGetCount()
    {
        /* @var EntityManagerInterface|MockInterface $entityManager */
        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getRepository')
            ->with(Task::class);

        $taskCollectionFilterService = new TaskCollectionFilterService($entityManager);

        $this->assertEquals(0, $taskCollectionFilterService->getCount());
    }

    /**
     * @param Test $test
     * @param string $taskType
     * @param string[] $states
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdCountByTestAndTaskTypeIncludingStatesTaskRepository(
        Test $test,
        $taskType,
        $states
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdCountByTestAndTaskTypeIncludingStates')
            ->with($test, $taskType, $states)
            ->andReturn(0);

        return $taskRepository;
    }

    /**
     * @param Test $test
     * @param string[] $excludeStates
     * @param string $taskType
     * @param string $issueCount
     * @param string $issueType
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        $excludeStates,
        $taskType,
        $issueCount,
        $issueType
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates')
            ->with($test, $excludeStates, $taskType, $issueCount, $issueType)
            ->andReturn(0);

        return $taskRepository;
    }

    /**
     * @param Test $test
     * @param string $taskType
     * @param string[] $states
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdByTestAndTaskTypeIncludingStatesTaskRepository(
        Test $test,
        $taskType,
        $states
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdByTestAndTaskTypeIncludingStates')
            ->with($test, $taskType, $states)
            ->andReturn([]);

        return $taskRepository;
    }

    /**
     * @param Test $test
     * @param string $expectedTaskType
     * @param string $expectedIssueCount
     * @param string $expectedIssueType
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        ?string $expectedTaskType,
        $expectedIssueCount,
        $expectedIssueType
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates')
            ->with($test, $this->excludedStates, $expectedTaskType, $expectedIssueCount, $expectedIssueType)
            ->andReturn([]);

        return $taskRepository;
    }

    /**
     * @return MockInterface|TaskRepository
     */
    private function createTaskRepository()
    {
        $taskRepository = \Mockery::mock(TaskRepository::class);

        return $taskRepository;
    }

    /**
     * @param TaskRepository $taskRepository
     *
     * @return MockInterface|EntityManagerInterface
     */
    private function createEntityManager(TaskRepository $taskRepository)
    {
        $entityManager = \Mockery::mock(EntityManagerInterface::class);
        $entityManager
            ->shouldReceive('getRepository')
            ->with(Task::class)
            ->andReturn($taskRepository);

        return $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );

        \Mockery::close();
    }
}
