<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services;

use Doctrine\ORM\EntityManagerInterface;
use Mockery\MockInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use SimplyTestable\WebClientBundle\Services\TaskCollectionFilterService;

class TaskCollectionFilterServiceTest extends \PHPUnit_Framework_TestCase
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
     * @param $typeFilter
     * @param $outcomeFilter
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

        $taskCollectionFilterService->getRemoteIdCount();
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
                        '= 0',
                        'error',
                        null,
                        $this->excludedStates
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
                        '> 0',
                        'error',
                        null,
                        []
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
                        '> 0',
                        'warning',
                        null,
                        []
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

        $taskCollectionFilterService->getRemoteIds();
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
                        '= 0',
                        'error',
                        null
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
                        '> 0',
                        'error',
                        null
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
                        '> 0',
                        'warning',
                        null
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
        /* @var EntityManagerInterface $entityManager */
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
            ->andReturn([]);

        return $taskRepository;
    }

    /**
     * @param Test $test
     * @param string $issueCount
     * @param string $issueType
     * @param string $taskType
     * @param string[] $excludeStates
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        $issueCount,
        $issueType,
        $taskType,
        $excludeStates
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates')
            ->with($test, $issueCount, $issueType, $taskType, $excludeStates)
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
            ->andReturn(0);

        return $taskRepository;
    }

    /**
     * @param Test $test
     * @param string $issueCount
     * @param string $issueType
     * @param string $taskType
     *
     * @return TaskRepository
     */
    private function createGetRemoteIdByTestAndIssueCountAndTaskTypeExcludingStatesTaskRepository(
        Test $test,
        $issueCount,
        $issueType,
        $taskType
    ) {
        $taskRepository = $this->createTaskRepository();
        $taskRepository
            ->shouldReceive('getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates')
            ->with($test, $issueCount, $issueType, $taskType, $this->excludedStates)
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

        \Mockery::close();
    }
}
