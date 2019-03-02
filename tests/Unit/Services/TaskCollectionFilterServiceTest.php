<?php
/** @noinspection PhpDocSignatureInspection */

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
     */
    public function testGetRemoteIdCount(
        EntityManagerInterface $entityManager,
        Test $test,
        ?string $typeFilter,
        string $outcomeFilter
    ) {
        $taskCollectionFilterService = new TaskCollectionFilterService($entityManager);

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setTypeFilter($typeFilter);
        $taskCollectionFilterService->setOutcomeFilter($outcomeFilter);

        $this->assertSame(0, $taskCollectionFilterService->getRemoteIdCount());
    }

    public function getRemoteIdCountDataProvider(): array
    {
        $test = Test::create(1, 'http://example.com/');

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
     */
    public function testGetRemoteIds(
        EntityManagerInterface $entityManager,
        Test $test,
        ?string $typeFilter,
        string $outcomeFilter
    ) {
        $taskCollectionFilterService = new TaskCollectionFilterService($entityManager);

        $taskCollectionFilterService->setTest($test);
        $taskCollectionFilterService->setTypeFilter($typeFilter);
        $taskCollectionFilterService->setOutcomeFilter($outcomeFilter);

        $this->assertSame([], $taskCollectionFilterService->getRemoteIds());
    }

    public function getRemoteIdsDataProvider(): array
    {
        $test = Test::create(1, 'http://example.com/');

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
     * @return TaskRepository|MockInterface
     */
    private function createGetRemoteIdCountByTestAndTaskTypeIncludingStatesTaskRepository(
        Test $test,
        ?string $taskType,
        array $states
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdCountByTestAndTaskTypeIncludingStates')
            ->with($test, $taskType, $states)
            ->andReturn(0);

        return $taskRepository;
    }

    /**
     * @return TaskRepository|MockInterface
     */
    private function createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        array $excludeStates,
        ?string $taskType,
        string $issueCount,
        string $issueType
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates')
            ->with($test, $excludeStates, $taskType, $issueCount, $issueType)
            ->andReturn(0);

        return $taskRepository;
    }

    /**
     * @return TaskRepository|MockInterface
     */
    private function createGetRemoteIdByTestAndTaskTypeIncludingStatesTaskRepository(
        Test $test,
        ?string $taskType,
        array $states
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdByTestAndTaskTypeIncludingStates')
            ->with($test, $taskType, $states)
            ->andReturn([]);

        return $taskRepository;
    }

    /**
     * @return TaskRepository|MockInterface
     */
    private function createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        ?string $expectedTaskType,
        string $expectedIssueCount,
        string $expectedIssueType
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
