<?php

namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;

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
    protected $taskRepository;

    /**
     * @var Test
     */
    private $test;

    /**
     * @var string
     */
    private $outcomeFilter = null;

    /**
     * @var string
     */
    private $typeFilter = null;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $this->taskRepository = $this->entityManager->getRepository(Task::class);
    }

    /**
     * @param Test $test
     */
    public function setTest(Test $test)
    {
        $this->test = $test;
    }

    /**
     * @param string $outcomeFilter
     */
    public function setOutcomeFilter($outcomeFilter)
    {
        $this->outcomeFilter = $outcomeFilter;
    }

    /**
     * @param string $typeFilter
     */
    public function setTypeFilter($typeFilter)
    {
        $this->typeFilter = $typeFilter;
    }

    /**
     * @return int
     */
    public function getRemoteIdCount()
    {
        if (in_array($this->outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdCountByTestAndTaskTypeIncludingStates(
                $this->test,
                $this->typeFilter,
                [$this->outcomeFilter]
            );
        }

        $excludeStates = [];
        if ($this->outcomeFilter === 'without-errors') {
            $excludeStates = [
                Task::STATE_SKIPPED,
                Task::STATE_CANCELLED,
                Task::STATE_IN_PROGRESS,
                Task::STATE_AWAITING_CANCELLATION,
            ];
        }

        return $this->taskRepository->getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
            $this->test,
            $this->createIssueCountFromOutcomeFilter(),
            $this->createIssueTypeFromOutcomeFilter(),
            $this->typeFilter,
            $excludeStates
        );
    }

    /**
     * @return int[]
     */
    public function getRemoteIds()
    {
        if (in_array($this->outcomeFilter, [self::OUTCOME_FILTER_SKIPPED, self::OUTCOME_FILTER_CANCELLED])) {
            return $this->taskRepository->getRemoteIdByTestAndTaskTypeIncludingStates(
                $this->test,
                $this->typeFilter,
                array($this->outcomeFilter)
            );
        }

        return $this->taskRepository->getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
            $this->test,
            $this->createIssueCountFromOutcomeFilter(),
            $this->createIssueTypeFromOutcomeFilter(),
            $this->typeFilter,
            [
                Task::STATE_SKIPPED,
                Task::STATE_CANCELLED,
                Task::STATE_IN_PROGRESS,
                Task::STATE_AWAITING_CANCELLATION,
            ]
        );
    }

    /**
     * @return string
     */
    private function createIssueCountFromOutcomeFilter()
    {
        if (empty($this->outcomeFilter)) {
            return null;
        }

        $outcomeFilterContainsWithout = substr_count($this->outcomeFilter, 'without') > 0;
        $comparison = $outcomeFilterContainsWithout
            ? '='
            : '>';

        return sprintf(
            '%s 0',
            $comparison
        );
    }

    /**
     * @return string
     */
    private function createIssueTypeFromOutcomeFilter()
    {
        return $this->outcomeFilter === 'with-warnings'
            ? 'warning'
            : 'error';
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return 0;
    }
}
