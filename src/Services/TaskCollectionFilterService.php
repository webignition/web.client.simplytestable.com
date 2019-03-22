<?php

namespace App\Services;

use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Task;
use App\Repository\TaskRepository;

class TaskCollectionFilterService
{
    const OUTCOME_FILTER_SKIPPED = 'skipped';
    const OUTCOME_FILTER_CANCELLED = 'cancelled';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var string
     */
    private $typeFilter = '';

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->taskRepository = $this->entityManager->getRepository(Task::class);
    }

    public function setTypeFilter(string $typeFilter = '')
    {
        $this->typeFilter = $typeFilter;
    }

    public function getRemoteIdCount(Test $test, string $outcomeFilter): int
    {
        if (in_array($outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdCountByTestAndTaskTypeIncludingStates(
                $test,
                $this->typeFilter,
                [$outcomeFilter]
            );
        }

        $excludeStates = [];
        if ($outcomeFilter === 'without-errors') {
            $excludeStates = [
                Task::STATE_SKIPPED,
                Task::STATE_CANCELLED,
                Task::STATE_IN_PROGRESS,
                Task::STATE_AWAITING_CANCELLATION,
            ];
        }

        return $this->taskRepository->getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            $excludeStates,
            $this->typeFilter,
            $this->createIssueCountFromOutcomeFilter($outcomeFilter),
            $this->createIssueTypeFromOutcomeFilter($outcomeFilter)
        );
    }

    /**
     * @param Test $test
     * @param string $outcomeFilter
     *
     * @return int[]
     */
    public function getRemoteIds(Test $test, string $outcomeFilter): array
    {
        if (in_array($outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdByTestAndTaskTypeIncludingStates(
                $test,
                $this->typeFilter,
                array($outcomeFilter)
            );
        }

        return $this->taskRepository->getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
            $test,
            [
                Task::STATE_SKIPPED,
                Task::STATE_CANCELLED,
                Task::STATE_IN_PROGRESS,
                Task::STATE_AWAITING_CANCELLATION,
            ],
            $this->typeFilter,
            $this->createIssueCountFromOutcomeFilter($outcomeFilter),
            $this->createIssueTypeFromOutcomeFilter($outcomeFilter)
        );
    }

    private function createIssueCountFromOutcomeFilter(string $outcomeFilter): string
    {
        if (empty($outcomeFilter)) {
            return '';
        }

        $outcomeFilterContainsWithout = substr_count($outcomeFilter, 'without') > 0;
        $comparison = $outcomeFilterContainsWithout
            ? '='
            : '>';

        return sprintf(
            '%s 0',
            $comparison
        );
    }

    private function createIssueTypeFromOutcomeFilter(string $outcomeFilter): string
    {
        return $outcomeFilter === 'with-warnings'
            ? 'warning'
            : 'error';
    }
}
