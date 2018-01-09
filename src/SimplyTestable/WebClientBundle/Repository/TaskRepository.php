<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

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
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->setParameter('Test', $test);
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
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task');
        $queryBuilder->where('Task.test = :Test');

        if (count($taskIds)) {
            $queryBuilder->andWhere('Task.taskId IN ('.implode(',', $taskIds).')');
        }

        $queryBuilder->setParameter('Test', $test);

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
        $queryBuilder = $this->createQueryBuilder('Task');
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
     * @param int $taskId
     *
     * @return bool
     */
    public function hasByTaskId($taskId)
    {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('count(Task.id)');
        $queryBuilder->where('Task.taskId = :TaskId');
        $queryBuilder->setParameter('TaskId', $taskId);

        $result = $queryBuilder->getQuery()->getResult();

        return $result[0][1] > 0;
    }

    /**
     * @param Test $test
     *
     * @return int[]
     */
    public function findRetrievedRemoteTaskIds(Test $test)
    {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->setParameter('Test', $test);

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
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');

        $where = '(Task.test = :Test AND (Task.state IN (:States)))';

        $queryBuilder->setParameter('Test', $test);
        $queryBuilder->setParameter('States', $states);

        if (!is_null($taskType)) {
            $where .= ' AND Task.type = :TaskType';
            $queryBuilder->setParameter('TaskType', $taskType);
        }

        $queryBuilder->where($where);

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
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
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->join('Task.output', 'TaskOutput');
        $queryBuilder->select('Task.taskId');

        $where = 'Task.test = :Test ';
        $queryBuilder->setParameter('Test', $test);

        if (!is_null($issueCount)) {
            $issueComparatorAndCount = explode(' ', $issueCount);
            $where .= ' AND TaskOutput.'.$issueType.'Count '.$issueComparatorAndCount[0].' :IssueCount';
            $queryBuilder->setParameter('IssueCount', $issueComparatorAndCount[1]);
        }

        $where .= ' AND (Task.state NOT IN (:States))';
        $queryBuilder->setParameter('States', $excludeStates);

        if (!is_null($taskType)) {
            $where .= ' AND Task.type = :TaskType';
            $queryBuilder->setParameter('TaskType', $taskType);
        }

        $queryBuilder->where($where);

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
    }


    public function getRemoteIdCountByTestAndTaskTypeIncludingStates(Test $test, $taskType = null, $states = array()) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('COUNT(Task.taskId)');

        $stateConditions = array();
        foreach ($states as $stateIndex => $state) {
            $stateConditions[] = '(Task.state = :State'.$stateIndex.') ';
            $queryBuilder->setParameter('State'.$stateIndex, $state);
        }

        $where = '(Task.test = :Test AND ('.implode('OR', $stateConditions).'))';

        if (!is_null($taskType)) {
            $where .= ' AND Task.type = :TaskType';
            $queryBuilder->setParameter('TaskType', $taskType);
        }

        $queryBuilder->where($where);
        $queryBuilder->setParameter('Test', $test);

        $result = $queryBuilder->getQuery()->getResult();
        return (int)$result[0][1];
    }


    public function getRemoteIdCountByTestAndIssueCountAndTaskTypeExcludingStates(Test $test, $issueCount = null, $issueType = null, $taskType, $excludeStates = array()) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('COUNT(Task.taskId)');

        $where = 'Task.test = :Test ';
        $queryBuilder->setParameter('Test', $test);

        if (!is_null($issueCount)) {
            $queryBuilder->join('Task.output', 'TaskOutput');
            $issueComparatorAndCount = explode(' ', $issueCount);
            $where .= ' AND TaskOutput.'.$issueType.'Count '.$issueComparatorAndCount[0].' :IssueCount';
            $queryBuilder->setParameter('IssueCount', $issueComparatorAndCount[1]);
        }

        if (is_array(($excludeStates))) {
            $stateConditions = array();

            foreach ($excludeStates as $stateIndex => $state) {
                $stateConditions[] = '(Task.state != :State'.$stateIndex.') ';
                $queryBuilder->setParameter('State'.$stateIndex, $state);
            }

            $where .= ' AND ('.implode('AND', $stateConditions).')';
        }

        if (!is_null($taskType)) {
            $where .= ' AND Task.type = :TaskType';
            $queryBuilder->setParameter('TaskType', $taskType);
        }

        $queryBuilder->where($where);

        $result = $queryBuilder->getQuery()->getResult();
        return (int)$result[0][1];
    }
}