<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;

class TaskService extends CoreApplicationService {    
    
    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\Task\Task';      
    
    /**
     *
     * @var EntityManager 
     */
    private $entityManager;    
    

    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $entityRepository;    
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TaskOutputService
     */
    private $taskOutputService;   
    
    
    public function __construct(
        EntityManager $entityManager,
        $parameters,
        \SimplyTestable\WebClientBundle\Services\WebResourceService $webResourceService,
        \SimplyTestable\WebClientBundle\Services\TaskOutputService $taskOutputService
    ) {
        parent::__construct($parameters, $webResourceService);
        $this->entityManager = $entityManager; 
        $this->taskOutputService = $taskOutputService;
    }
    
    
    /**
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }
        
        return $this->entityRepository;
    }
    
    
    /**
     *
     * @param Test $test
     * @param int $task_id
     * @return Task 
     */
    public function get(Test $test, $task_id) {
        $task = $this->getEntityRepository()->findOneBy(array(
            'id' => $task_id,
            'test' => $test
        ));
        
        if ($task == null) {
            return $task;
        }
        
        return $this->taskOutputService->setParsedOutput($task);        
    }
    
    
    /**
     *
     * @param Test $test
     * @param array $task_ids
     * @return Task 
     */
    public function getCollection(Test $test, $task_ids) {
        $tasks = $this->getEntityRepository()->findBy(array(
            'id' => $task_ids,
            'test' => $test
        ));
        
        foreach ($tasks as $task) {
            $this->taskOutputService->setParsedOutput($task); 
        }
        
        return $tasks;
    }
    
    
    /**
     *
     * @param Task $task
     * @return \SimplyTestable\WebClientBundle\Entity\Task\Task 
     */
    public function markCancelled(Task $task) {
        $task->setState('cancelled');
        $this->entityManager->persist($task);
        $this->entityManager->flush(); 
        
        return $task;
    }
    
    
    /**
     * 
     * @param array $taskIds
     * @return array 
     */
    public function getRemoteTaskIds($taskIds) {
            $queryBuilder = $this->getEntityRepository()->createQueryBuilder('Task');
            $queryBuilder->select('Task.taskId');
            $queryBuilder->where('Task.id IN ('.  implode(',', $taskIds).')');
            
            $result = $queryBuilder->getQuery()->getResult();        
            
            $remoteTaskIds = array();
            foreach ($result as $resultItem) {
                $remoteTaskIds[] = (int)$resultItem['taskId'];
            }
            
            return $remoteTaskIds;
    }
    
}