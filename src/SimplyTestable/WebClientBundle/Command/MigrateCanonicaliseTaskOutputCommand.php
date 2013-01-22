<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class MigrateCanonicaliseTaskOutputCommand extends BaseCommand
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
            ->setName('simplytestable:migrate:canonicalise-task-output')
            ->setDescription('Update tasks to point to canoical output')
            ->addOption('limit')
            ->addOption('dry-run')             
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $output->writeln('Finding duplicate output ...');
        
        $duplicateHashes = $this->getTaskOutputRepository()->findDuplicateHashes($this->getLimit($input));       
        
        if (count($duplicateHashes) === 0) {
            $output->writeln('No duplicate output found. Done.');
            return true;
        }
        
        $output->writeln('Processing ' . count($duplicateHashes) . ' duplicate hashes');
        $globalUpdatedTaskCount = 0;
        
        foreach ($duplicateHashes as $duplicateHash) {
            $outputIds = $this->getTaskOutputRepository()->findIdsBy($duplicateHash);
            
            $output->writeln('['.(count($outputIds) - 1) . '] duplicates found for '.$duplicateHash);
            
            if (count($outputIds) > 1) {                
                $sourceId = $outputIds[0];
                $sourceOutput = $this->getTaskOutputRepository()->find($sourceId);
                $duplicatesToRemove = array_slice($outputIds, 1);
                $updatedTaskCount = 0;
                
                foreach ($duplicatesToRemove as $taskOutputId) {                    
                    $taskOutput = $this->getTaskOutputRepository()->find($taskOutputId);
                    
                    $task = $this->getTaskRepository()->findOneBy(array(
                        'output' => $taskOutput
                    ));
                    
                    if ($task) {
                        $output->writeln('Updating output for task ['.$task->getId().']');
                        $updatedTaskCount++;

                        if (!$this->isDryRun($input)) {
                            $task->setOutput($sourceOutput);
                            $this->getEntityManager()->persist($task);
                            $this->getEntityManager()->flush($task);                        
                        }                        
                    }                   
                }
                
                if ($updatedTaskCount === 0) {
                    $output->writeln('No tasks using duplicates of ' . $duplicateHash);
                }
                
                $globalUpdatedTaskCount += $updatedTaskCount;
                
                $output->writeln('');             
            }
        }
        
        $output->writeln('['.$globalUpdatedTaskCount.'] tasks updated');
        
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