<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class MigrateSetTaskOutputIdCommand extends BaseCommand
{
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Repository\TaskRepository
     */
    private $taskRepository;
    
    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $taskOutputRepository;
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:migrate:set-task-output-id')
            ->setDescription('Set the output_id property on all Tasks that have output but where the relationship exists in the TaskOutput table')
            ->addOption('limit');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    { 
        $output->writeln('Finding tasks that need output_id migrated ...');
        
        $tasks = $this->getTasksRequiringMigration($this->getLimit($input));
        
        if (count($tasks) === 0) {
            $output->writeln('No tasks require output migration. Done.');
            return true;
        }
        
        $output->writeln(count($tasks).' tasks require output migration.');
        
        foreach ($tasks as $task) {
            /* @var $task Task */            
            $output = $this->getTaskOutputRepository()->findOneBy(array(
                'task' => $task
            ));
            
            if (!is_null($output)) {
                $task->setOutput($output);
                $this->getEntityManager()->persist($task);
            }
        }
        
        $this->getEntityManager()->flush();
        
        return true;
    }
    
    
    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return int
     */
    private function getLimit(InputInterface $input) {
        if ($input->getOption('limit') === false) {
            return 0;
        }
        
        $limit = filter_var($input->getOption('limit'), FILTER_VALIDATE_INT);
        
        return ($limit <= 0) ? 0 : $limit;
    }
    
    
    /**
     * Get collection of tasks that need the output set
     * 
     * @param int $limit
     * @return array
     */
    private function getTasksRequiringMigration($limit = 0) {        
        $queryBuilder = $this->getTaskRepository()->createQueryBuilder('Task');
        
        $queryBuilder->select('Task');
        $queryBuilder->where('Task.output IS NULL');
        
        if (is_int($limit) && $limit > 0) {
            $queryBuilder->setMaxResults($limit);
        }
        
        return $queryBuilder->getQuery()->getResult();     
    }
    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager() {
        if (is_null($this->entityManager)) {
            $this->entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
        }
        
        return  $this->entityManager;
    }
    
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Repository\TaskRepository
     */
    private function getTaskRepository() {
        if (is_null($this->taskRepository)) {
            $this->taskRepository = $this->getEntityManager()->getRepository('SimplyTestable\WebClientBundle\Entity\Task\Task');
        }
        
        return $this->taskRepository;
    }
    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getTaskOutputRepository() {
        if (is_null($this->taskOutputRepository)) {
            $this->taskOutputRepository = $this->getEntityManager()->getRepository('SimplyTestable\WebClientBundle\Entity\Task\Output');
        }
        
        return $this->taskOutputRepository;
    }
}