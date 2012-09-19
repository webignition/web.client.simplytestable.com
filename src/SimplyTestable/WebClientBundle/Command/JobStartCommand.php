<?php
namespace SimplyTestable\WebClientBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use webignition\Http\Client\Client as HttpClient;

class JobStartCommand extends BaseCommand
{
    private $knownGoodTests = array(       
                'http://corte.si/',
                'https://tindie.com/',
                'http://codeinthehole.com/',
                'http://themactivist.com/',
                'http://blog.simplytestable.com/',
                'http://webignition.net/',
                'http://www.sebastianmarshall.com/',
                'http://4chan.org/',
                'http://paulstamatiou.com/',
                'http://threesixty360.wordpress.com/',
                'http://blog.evanweaver.com/',
                'http://www.theatlantic.com/',
                'http://www.quirky.com/',
                'http://code.jonwagner.com/',
                'http://www.datomic.com/',
                'http://skytoaster.com/',
                'http://nikosbaxevanis.com/',
                'http://www.matchalabs.com/',
                'http://cardiffdevils.com/',
                'http://www.737challenge.com/',
                'http://voltrexfx.com/',
                'http://thestandpeople.co.uk/',
                'http://energypr.co.uk/',
                'http://budgetvets.co.uk/',
                'http://www.cavc.ac.uk/',
                'http://blog.notdot.net/',
                'http://www.eia.gov/',
                'http://theothereight.wordpress.com/',
                'http://blog.davor.se/',
                'http://www.phpmysqlfreelancer.com/',
                'http://www.digital-dd.com/',
                'http://www.benrady.com/',
                'http://lethain.com/',
                'http://tympanus.net/',
                'http://ukpersonaltrainers.com/',
                'http://scottlocklin.wordpress.com/',
                'http://gowers.wordpress.com/',
                'http://terrytao.wordpress.com/',
                'http://orwelldiaries.wordpress.com/',
                'http://marg09.wordpress.com/',
                'http://stevemccurry.wordpress.com/',
                'http://www.somersetwebservices.co.uk/',
                'http://bosker.wordpress.com/',
                'http://bhorowitz.com/',
                'http://www.jacquesmattheij.com/',
                'http://ceronman.com',
                'http://yourhamper.com/',
                'http://codinghorror.com/blog/'
            );
    
    
    /**
     *
     * @var string
     */
    private $httpFixturePath;    
    
    protected function configure()
    {
        $this
            ->setName('simplytestable:job:start')
            ->setDescription('Start a new job from a list of known-good jobs if there are no recent running jobs')
            ->addArgument('http-fixture-path', InputArgument::OPTIONAL, 'path to HTTP fixture data when testing')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $recentTests = $this->getTestService()->getList(3)->getContentObject();
        $hasIncompleteRecentTests = false;
        
        $incompleteStates = array('new', 'preparing', 'queued', 'in-progress');
        
        foreach ($recentTests as $recentTest) {
            if (in_array($recentTest->state, $incompleteStates)) {
                $hasIncompleteRecentTests = true;
            }
        }
        
        if (!$hasIncompleteRecentTests) {
            $testCanonicalUrlIndex = rand(0, count($this->knownGoodTests) - 1);
            $testCanonicalUrl = $this->knownGoodTests[$testCanonicalUrlIndex];

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
     * @return HttpClient
     */     
    private function getHttpClient() {
        return $this->getContainer()->get('simplytestable.services.httpClient');
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
     * @return SimplyTestable\WebClientBundle\Services\TestService
     */        
    private function getTestService() {
        return $this->getContainer()->get('simplytestable.services.testService');
    }      
}