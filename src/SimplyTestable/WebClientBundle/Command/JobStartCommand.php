<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use webignition\Http\Client\Client as HttpClient;

class JobStartCommand extends BaseCommand
{
    private $knownGoodTests = null;   
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:job:start')
            ->setDescription('Start a new job from a list of known-good jobs if there are no recent running jobs')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $this->getTestService()->setUser($this->getUserService()->getPublicUser());
        $recentTests = $this->getTestService()->getList(3)->getContentObject();
        $hasIncompleteRecentTests = false;
        
        $incompleteStates = array('new', 'preparing', 'queued', 'in-progress');
        
        foreach ($recentTests as $recentTest) {
            if (in_array($recentTest->state, $incompleteStates)) {
                $hasIncompleteRecentTests = true;
            }
        }
        
        if (!$hasIncompleteRecentTests) {
            $recentTestUrls = array();
            foreach ($recentTests as $recentTest) {
                $recentTestUrls[] = $recentTest->website;
            }
            
            $knownGoodTests = $this->getKnownGoodTests();
            
            $testCanonicalUrl = $recentTestUrls[0];
            
            while (in_array($testCanonicalUrl, $recentTestUrls)) {
                $testCanonicalUrlIndex = rand(0, count($knownGoodTests) - 1);
                $testCanonicalUrl = $knownGoodTests[$testCanonicalUrlIndex];                
            }

            $this->getTestService()->start($testCanonicalUrl);  
            
            $this->getLogger()->info("simplytestable:job:start: started job for [".$testCanonicalUrl."]");
        }       
        
        if ($this->getResqueQueueService()->isEmpty('job-start')) {
            $this->getResqueQueueService()->add(
                'SimplyTestable\WebClientBundle\Resque\Job\StartNewTestJob',
                'job-start'
            );             
        }
    }

    
    /**
     *
     * @return SimplyTestable\WebClientBundle\Services\ResqueQueueService
     */        
    private function getResqueQueueService() {
        return $this->getContainer()->get('simplytestable.services.resqueQueueService');
    }  
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\TestService
     */        
    private function getTestService() {
        return $this->getContainer()->get('simplytestable.services.testService');
    } 
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\UserService
     */        
    private function getUserService() {
        return $this->getContainer()->get('simplytestable.services.userservice');
    }     
    
    
    /**
     * 
     * @return array
     */
    private function getKnownGoodTests() {
        if (is_null($this->knownGoodTests)) {
            $this->knownGoodTests = array();
            
            $rawKnownGoodTests = file($this->getContainer()->get('kernel')->locateResource('@SimplyTestableWebClientBundle/Resources/config/knownGoodTests.txt'));
            foreach ($rawKnownGoodTests as $rawKnownGoodTest) {
                if (!in_array($rawKnownGoodTest, $this->knownGoodTests)) {
                    $this->knownGoodTests[] = trim($rawKnownGoodTest);
                }
            }
            
        }
        
        return $this->knownGoodTests;
    }
}