<?php
namespace SimplyTestable\WebClientBundle\Command\Maintenance;

use SimplyTestable\WebClientBundle\Command\BaseCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class MaintenanceResumeAfterReadOnlyCommand extends BaseCommand
{
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private $testQueueService;   
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:maintenance:resume-after-read-only')
            ->setDescription('Resume operations after core application has returned to an active state after being read-only')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $queuedTests = $this->getTestQueueService()->listTests();
        
        foreach ($queuedTests as $queuedTest) {            
            try {
                $this->getTestService()->setUser($queuedTest['user']);
                $this->getTestService()->start($queuedTest['url'], $queuedTest['options'], $queuedTest['type'])->getContentObject();
                $this->getTestQueueService()->dequeue($queuedTest['user'], $queuedTest['url']);
            } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
                if ($webResourceServiceException->getCode() == 503) {
                }
            }
        }
        
/*

        try {
            $jsonResponseObject = $this->getTestService()->start($this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'))->getContentObject();
            return $this->redirect($this->generateUrl(
                'app_progress',
                array(
                    'website' => $jsonResponseObject->website,
                    'test_id' => $jsonResponseObject->id
                ),
                true
            ));
        } catch (\SimplyTestable\WebClientBundle\Exception\WebResourceServiceException $webResourceServiceException) {
            if ($webResourceServiceException->getCode() == 503) {                               
                $this->getTestQueueService()->enqueue($this->getUser(), $this->getTestUrl(), $testOptions, ($this->isFullTest() ? 'full site' : 'single url'));                
                return $this->redirect($this->generateUrl(
                    'app_website',
                    array(
                        'website' => $this->getTestUrl()                        
                    ),
                    true
                ));                
            }
        }

 */        
        
        
        
//        $this->getTestService()->setUser($this->getUserService()->getPublicUser());
//        $recentTests = $this->getTestService()->getList(3)->getContentObject();
//        $hasIncompleteRecentTests = false;
//        
//        $incompleteStates = array('new', 'preparing', 'queued', 'in-progress');
//        
//        foreach ($recentTests as $recentTest) {
//            if (in_array($recentTest->state, $incompleteStates)) {
//                $hasIncompleteRecentTests = true;
//            }
//        }
//        
//        if (!$hasIncompleteRecentTests) {
//            $recentTestUrls = array();
//            foreach ($recentTests as $recentTest) {
//                $recentTestUrls[] = $recentTest->website;
//            }
//            
//            $knownGoodTests = $this->getKnownGoodTests();
//            
//            $testCanonicalUrl = $recentTestUrls[0];
//            
//            while (in_array($testCanonicalUrl, $recentTestUrls)) {
//                $testCanonicalUrlIndex = rand(0, count($knownGoodTests) - 1);
//                $testCanonicalUrl = $knownGoodTests[$testCanonicalUrlIndex];                
//            }
//            
//            $testOptions = new \SimplyTestable\WebClientBundle\Model\TestOptions();
//            $testOptions->addTestType('HTML validation');
//
//            $this->getTestService()->start($testCanonicalUrl, $testOptions);  
//            
//            $this->getLogger()->info("simplytestable:job:start: started job for [".$testCanonicalUrl."]");
//        }       
//        
//        if ($this->getResqueQueueService()->isEmpty('job-start')) {
//            $this->getResqueQueueService()->add(
//                'SimplyTestable\WebClientBundle\Resque\Job\StartNewTestJob',
//                'job-start'
//            );             
//        }
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
     * @return \SimplyTestable\WebClientBundle\Services\TestQueueService
     */
    private function getTestQueueService() {
        if (is_null($this->testQueueService)) {
            $this->testQueueService = $this->getContainer()->get('simplytestable.services.testqueueservice');
            $this->testQueueService->setApplicationRootDirectory($this->getContainer()->get('kernel')->getRootDir());
                    
        }
        
        return $this->testQueueService;

    }  
}