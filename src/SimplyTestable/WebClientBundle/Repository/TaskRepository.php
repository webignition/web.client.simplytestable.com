<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class TaskRepository extends EntityRepository
{
    public function getCollectionExistsByTestAndRemoteId(Test $test, $taskIds = array()) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->setParameter('Test', $test);
        $queryResult = $queryBuilder->getQuery()->getResult();

        $resultTaskIds = array();
        foreach ($queryResult as $resultItem) {
            $resultTaskIds[$resultItem['taskId']] = true;
        }

        $result = array();

        foreach ($taskIds as $taskId) {
            $result[$taskId] = isset($resultTaskIds[$taskId]);
        }

        return $result;
    }


    public function getCollectionByTestAndRemoteId(Test $test, $taskIds = array()) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task');
        $queryBuilder->where('Task.test = :Test');

        if (count($taskIds)) {
            $queryBuilder->andWhere('Task.taskId IN ('.implode(',', $taskIds).')');
        }

        $queryBuilder->setParameter('Test', $test);

        $queryResult = $queryBuilder->getQuery()->getResult();

        $tasks = array();

        foreach ($queryResult as $task) {
            $tasks[$task->getTaskId()] = $task;
        }

        return $tasks;
    }

    public function getCountByTestAndState(Test $test, $states) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('count(Task.id)');

        $stateConditions = array();

        foreach ($states as $stateIndex => $state) {
            $stateConditions[] = '(Task.state = :State'.$stateIndex.') ';
            $queryBuilder->setParameter('State'.$stateIndex, $state);
        }

        $queryBuilder->where('(Task.test = :Test AND ('.implode('OR', $stateConditions).'))');
        $queryBuilder->setParameter('Test', $test);

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }


    public function getErrorFreeCountByTest(Test $test) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->join('Task.output', 'TaskOutput');
        $queryBuilder->select('count(Task.id)');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->andWhere('TaskOutput.errorCount = :ErrorCount');

        $queryBuilder->setParameter('Test', $test);
        $queryBuilder->setParameter('ErrorCount', 0);

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }


    private function getTaskIdsFromQueryResult($resultSet) {
        $taskIds = array();

        foreach ($resultSet as $result) {
            $taskIds[] = $result['taskId'];
        }

        return $taskIds;
    }


    public function getErroredCountByTest(Test $test) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->join('Task.output', 'TaskOutput');
        $queryBuilder->select('count(Task.id)');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->andWhere('TaskOutput.errorCount > :ErrorCount');

        $queryBuilder->setParameter('Test', $test);
        $queryBuilder->setParameter('ErrorCount', 0);

        $result = $queryBuilder->getQuery()->getResult();

        return (int)$result[0][1];
    }


    public function findUsedTaskOutputIds() {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->join('Task.output', 'TaskOutput');
        $queryBuilder->select('DISTINCT TaskOutput.id as TaskOutputId');

        $result = $queryBuilder->getQuery()->getResult();

        if (count($result) === 0) {
            return array();
        }

        $ids = array();

        foreach ($result as $taskOutputIdResult) {
            $ids[] = $taskOutputIdResult['TaskOutputId'];
        }

        return $ids;
    }


    /**
     *
     * @param int $task_id
     * @return boolean
     */
    public function hasByTaskId($task_id) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('count(Task.id)');
        $queryBuilder->where('Task.taskId = :TaskId');
        $queryBuilder->setParameter('TaskId', $task_id);

        $result = $queryBuilder->getQuery()->getResult();

        return $result[0][1] > 0;
    }


    /**
     *
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return array
     */
    public function findRetrievedRemoteTaskIds(Test $test) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');
        $queryBuilder->where('Task.test = :Test');
        $queryBuilder->setParameter('Test', $test);

        $result = $queryBuilder->getQuery()->getResult();

        if (count($result) === 0) {
            return array();
        }

        return $this->getSingleFieldCollectionFromResult($result, 'taskId');
    }


    /**
     *
     * @param array $result
     * @param string $fieldName
     * @return array
     */
    private function getSingleFieldCollectionFromResult($result, $fieldName) {
        $values = array();

        foreach ($result as $resultItem) {
            $values[] = $resultItem[$fieldName];
        }

        return $values;
    }


    public function getRemoteIdByTestAndTaskTypeIncludingStates(Test $test, $taskType = null, $states = array()) {
        $queryBuilder = $this->createQueryBuilder('Task');
        $queryBuilder->select('Task.taskId');

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

        return $this->getTaskIdsFromQueryResult($queryBuilder->getQuery()->getResult());
    }


    public function getRemoteIdByTestAndIssueCountAndTaskTypeExcludingStates(Test $test, $issueCount = null, $issueType = null, $taskType, $excludeStates = array()) {
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