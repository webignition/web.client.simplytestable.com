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
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

    public function getRemoteIdCount(Test $test, string $outcomeFilter, string $typeFilter): int
    {
        if (in_array($outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdCountByTestAndTaskTypeIncludingStates(
                $test,
                $typeFilter,
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
            $typeFilter,
            $this->createIssueCountFromOutcomeFilter($outcomeFilter),
            $this->createIssueTypeFromOutcomeFilter($outcomeFilter)
        );
    }

    /**
     * @param Test $test
     * @param string $outcomeFilter
     * @param string $typeFilter
     *
     * @return int[]
     */
    public function getRemoteIds(Test $test, string $outcomeFilter, string $typeFilter): array
    {
        if (in_array($outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdByTestAndTaskTypeIncludingStates(
                $test,
                $typeFilter,
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
            $typeFilter,
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
