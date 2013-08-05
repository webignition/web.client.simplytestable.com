<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class MigrateRemoveUnusedOutputCommand extends BaseCommand
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
            ->setName('simplytestable:migrate:remove-unused-output')
            ->setDescription('Remove output not linked to any task')
            ->addOption('limit')
            ->addOption('dry-run')             
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $output->writeln('Finding unsued output ...');
        
        $usedTaskOutputIds = $this->getTaskRepository()->findUsedTaskOutputIds();
        $unusedTaskOutputIds = $this->getTaskOutputRepository()->findIdsNotIn($usedTaskOutputIds);
        
        if (count($unusedTaskOutputIds) === 0) {
            $output->writeln('No unused task outputs found. Done.');
            return true;
        }   
        
        $output->writeln('['.count($unusedTaskOutputIds).'] outputs found');
        
        foreach ($unusedTaskOutputIds as $unusedTaskOutputId) {
            $taskOutputToRemove = $this->getTaskOutputRepository()->find($unusedTaskOutputId);
            
            $output->writeln('Removing output ['.$unusedTaskOutputId.']');
            
            if (!$this->isDryRun($input)) {
                $this->getEntityManager()->remove($taskOutputToRemove);
                $this->getEntityManager()->flush();
            }
        }
        
        return true;
    }
    
    
    
    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return int
     */
    private function isDryRun(InputInterface $input) {
        return $input->getOption('dry-run');
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
     * @return \SimplyTestable\WebClientBundle\Repository\TaskOutputRepository
     */
    private function getTaskOutputRepository() {
        if (is_null($this->taskOutputRepository)) {
            $this->taskOutputRepository = $this->getEntityManager()->getRepository('SimplyTestable\WebClientBundle\Entity\Task\Output');
        }
        
        return $this->taskOutputRepository;
    }
}