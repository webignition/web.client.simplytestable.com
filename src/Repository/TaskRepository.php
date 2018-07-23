<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use App\Entity\Task\Task;
use App\Entity\Test\Test;

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
     * @param string|null $taskType
     * @param string[] $states
     *
     * @return int[]
     */
    public function getRemoteIdByTestAndTaskTypeIncludingStates(Test $test, $taskType = null, $states = [])
    {
        $queryBuilder = $this->createTestAndTaskTypeIncludingStatesQueryBuilder($test, $taskType, $states);
        $queryBuilder->select('Task.taskId');

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
    }

    /**
     * @param Test $test
     * @param string|null $taskType
     * @param string[] $states
     *
     * @return int
     */
    public function getRemoteIdCountByTestAndTaskTypeIncludingStates(Test $test, $taskType = null, $states = [])
    {
        $queryBuilder = $this->createTestAndTaskTypeIncludingStatesQueryBuilder($test, $taskType, $states);
        $queryBuilder->select('COUNT(Task.taskId)');

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }

    /**
     * @param Test $test
     * @param string|null $taskType
     * @param string[] $states
     *
     * @return QueryBuilder
     */
    private function createTestAndTaskTypeIncludingStatesQueryBuilder(Test $test, $taskType = null, $states = [])
    {
        $queryBuilder = $this->createTaskQueryBuilder();
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        $queryBuilder->andWhere('Task.state IN (:States)');
        $queryBuilder->setParameter('States', $states);

        if (!is_null($taskType)) {
            $queryBuilder->andWhere('Task.type = :TaskType');
            $queryBuilder->setParameter('TaskType', $taskType);
        }



        return $queryBuilder;
    }

    /**
     * @param Test $test
     * @param string|null $issueCount
     * @param string|null $issueType
     * @param string|null $taskType
     * @param array $excludeStates
     *
     * @return int[]
     */
    public function getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(
        Test $test,
        $issueCount = null,
        $issueType = null,
        $taskType,
        array $excludeStates
    ) {
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

    /**
     * @param Test $test
     * @param string|null $issueCount
     * @param string|null $issueType
     * @param string|null $taskType
     * @param string[] $excludeStates
     *
     * @return int
     */
    public function getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(
        Test $test,
        $issueCount = null,
        $issueType = null,
        $taskType,
        array $excludeStates
    ) {
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

    /**
     * @param Test $test
     * @param string[] $excludeStates
     * @param string|null $taskType
     * @param string|null $issueCount
     * @param string|null $issueType
     *
     * @return QueryBuilder
     */
    private function createTestAndIssueCountAndTaskTypeExcludingStatesQueryBuilder(
        Test $test,
        array $excludeStates,
        $taskType = null,
        $issueCount = null,
        $issueType = null
    ) {
        $queryBuilder = $this->createTaskQueryBuilder();
        $this->setQueryBuilderTaskTestPredicate($queryBuilder, $test);

        if (!is_null($issueCount)) {
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
