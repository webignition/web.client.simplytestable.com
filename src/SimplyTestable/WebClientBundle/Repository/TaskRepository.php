<?php
namespace SimplyTestable\WebClientBundle\Repository;

use Doctrine\ORM\EntityRepository;
use SimplyTestable\WebClientBundle\Entity\Test\Test;

class TaskRepository extends EntityRepository
{  
    public function getExistsByTestAndRemoteId(Test $test, $taskId) {
        return !is_null($this->findOneBy(array(
            'taskId' => $taskId,
            'test' => $test
        )));      
    }
    
    
    public function getCollectionExistsByTestAndRemoteId(Test $test, $taskIds = array()) {
        $result = array();
        
        foreach ($taskIds as $taskId) {
            $result[$taskId] = $this->getExistsByTestAndRemoteId($test, $taskId);
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
}