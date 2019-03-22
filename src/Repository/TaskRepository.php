<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Task\Task;
use App\Entity\Test;

class TaskRepository extends EntityRepository
{
    /**
     * @param Test $test
     * @param int[] $taskIds
     *
     * @return bool[]
     */
    public function getCollectionExistsByTestAndRemoteId(Test $test, $taskIds = [])
    {
        $queryBuilder = $this->createTaskQueryBuilder();
        $queryBuilder->select('Task.taskId');

        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        $queryResult = $queryBuilder->getQuery()->getResult();

        $resultTaskIds = [];
        foreach ($queryResult as $resultItem) {
            $resultTaskIds[$resultItem['taskId']] = true;
        }

        $result = [];

        foreach ($taskIds as $taskId) {
            $result[$taskId] = isset($resultTaskIds[$taskId]);
        }

        return $result;
    }

    /**
     * @param Test $test
     * @param int[] $taskIds
     *
     * @return Task[]
     */
    public function getCollectionByTestAndRemoteId(Test $test, $taskIds = [])
    {
        $queryBuilder = $this->createTaskQueryBuilder();
        $queryBuilder->select('Task');
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        if (count($taskIds)) {
            $queryBuilder->andWhere('Task.taskId IN ('.implode(',', $taskIds).')');
        }

        /* @var Task[] $queryResult */
        $queryResult = $queryBuilder->getQuery()->getResult();

        $tasks = [];

        foreach ($queryResult as $task) {
            $tasks[$task->getTaskId()] = $task;
        }

        return $tasks;
    }

    /**
     * @param array $resultSet
     *
     * @return int[]
     */
    private function getTaskIdsFromQueryResult($resultSet)
    {
        $taskIds = [];

        foreach ($resultSet as $result) {
            $taskIds[] = $result['taskId'];
        }

        return $taskIds;
    }

    /**
     * @return int[]
     */
    public function findUsedTaskOutputIds()
    {
        $queryBuilder = $this->createTaskQueryBuilder();
        $queryBuilder->join('Task.output', 'TaskOutput');
        $queryBuilder->select('DISTINCT TaskOutput.id as TaskOutputId');

        $result = $queryBuilder->getQuery()->getResult();

        if (count($result) === 0) {
            return [];
        }

        $ids = [];

        foreach ($result as $taskOutputIdResult) {
            $ids[] = $taskOutputIdResult['TaskOutputId'];
        }

        return $ids;
    }

    /**
     * @param Test $test
     *
     * @return int[]
     */
    public function findRetrievedRemoteTaskIds(Test $test)
    {
        $queryBuilder = $this->createTaskQueryBuilder();
        $queryBuilder->select('Task.taskId');
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        $queryResult = $queryBuilder->getQuery()->getResult();

        $remoteTaskIds = [];

        foreach ($queryResult as $taskIdResult) {
            $remoteTaskIds[] = $taskIdResult['taskId'];
        }

        return $remoteTaskIds;
    }

    /**
     * @param Test $test
     * @param string $taskType
     * @param string[] $states
     *
     * @return int[]
     */
    public function getRemoteIdByTestAndTaskTypeIncludingStates(Test $test, string $taskType, array $states = []): array
    {
        $queryBuilder = $this->createTestAndTaskTypeIncludingStatesQueryBuilder($test, $taskType, $states);
        $queryBuilder->select('Task.taskId');

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
    }

    public function getRemoteIdCountByTestAndTaskTypeIncludingStates(
        Test $test,
        string $taskType,
        array $states = []
    ): int {
        $queryBuilder = $this->createTestAndTaskTypeIncludingStatesQueryBuilder($test, $taskType, $states);
        $queryBuilder->select('COUNT(Task.taskId)');

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }

    private function createTestAndTaskTypeIncludingStatesQueryBuilder(
        Test $test,
        string $taskType,
        array $states = []
    ): QueryBuilder {
        $queryBuilder = $this->createTaskQueryBuilder();
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        $queryBuilder->andWhere('Task.state IN (:States)');
        $queryBuilder->setParameter('States', $states);

        if (!empty($taskType)) {
            $queryBuilder->andWhere('Task.type = :TaskType');
            $queryBuilder->setParameter('TaskType', $taskType);
        }



        return $queryBuilder;
    }

    /**
     * @param Test $test
     * @param array $excludeStates
     * @param string $taskType
     * @param string $issueCount
     * @param string $issueType
     *
     * @return int[]
     */
    public function getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
        Test $test,
        array $excludeStates,
        string $taskType,
        string $issueCount,
        string $issueType
    ):array {
        $queryBuilder = $this->createTestAndIssueCountAndTaskTypeExcludingStatesQueryBuilder(
            $test,
            $excludeStates,
            $taskType,
            $issueCount,
            $issueType
        );

        $queryBuilder->select('Task.taskId');

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
    }

    public function getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
        Test $test,
        array $excludeStates,
        string $taskType,
        string $issueCount,
        string $issueType
    ): int {
        $queryBuilder = $this->createTestAndIssueCountAndTaskTypeExcludingStatesQueryBuilder(
            $test,
            $excludeStates,
            $taskType,
            $issueCount,
            $issueType
        );

        $queryBuilder->select('COUNT(Task.taskId)');
        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }

    private function createTestAndIssueCountAndTaskTypeExcludingStatesQueryBuilder(
        Test $test,
        array $excludeStates,
        string $taskType,
        string $issueCount,
        string $issueType
    ): QueryBuilder {
        $queryBuilder = $this->createTaskQueryBuilder();
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        if (!empty($issueCount)) {
            $queryBuilder->join('Task.output', 'TaskOutput');
            $issueComparatorAndCount = explode(' ', $issueCount);

            $queryBuilder->andWhere(' TaskOutput.'.$issueType.'Count '.$issueComparatorAndCount[0].' :IssueCount');
            $queryBuilder->setParameter('IssueCount', $issueComparatorAndCount[1]);
        }

        if (!empty($excludeStates)) {
            $queryBuilder->andWhere('Task.state NOT IN (:States)');
            $queryBuilder->setParameter('States', $excludeStates);
        }

        if (!empty($taskType)) {
            $queryBuilder->andWhere('Task.type = :TaskType');
            $queryBuilder->setParameter('TaskType', $taskType);
        }

        return $queryBuilder;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param Test $test
     */
    private function setQueryBuilderTaskTestPredicate(QueryBuilder $queryBuilder, Test $test)
    {
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->setParameter('Test', $test);
    }

    /**
     * @return QueryBuilder
     */
    private function createTaskQueryBuilder()
    {
        return $this->createQueryBuilder('Task');
    }
}
