<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Output as TaskOutput;

class MigrateAddHashToHashlessOutputCommand extends BaseCommand
{
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     *
     * @var \Doctrine\ORM\EntityRepository
     */
    private $taskOutputRepository;
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:add-hash-to-hashless-output')
            ->setDescription('Set the hash property on TaskOutput objects that have no hash set')
            ->addOption('limit');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    { 
        $output->writeln('Finding hashless output ...');
        $hashlessOutputTasks = $this->getTaskOutputRepository()->findHashlessOutput($this->getLimit($input));
        
        if (count($hashlessOutputTasks) === 0) {
            $output->writeln('No task outputs require a hash to be set. Done.');
            return true;
        }
        
        $output->writeln(count($hashlessOutputTasks).' outputs require a hash to be set.');
        
        foreach ($hashlessOutputTasks as $output) {
            /* @var $output TaskOutput */            
            $output->generateHash();
            $this->getEntityManager()->persist($output);
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
     * @return \SimplyTestable\WebClientBundle\Repository\TaskOutputRepository
     */
    private function getTaskOutputRepository() {
        if (is_null($this->taskOutputRepository)) {
            $this->taskOutputRepository = $this->getEntityManager()->getRepository('SimplyTestable\WebClientBundle\Entity\Task\Output');
        }
        
        return $this->taskOutputRepository;
    }
}