<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;

use webignition\NormalisedUrl\NormalisedUrl;


class TestService {    
    
    const ENTITY_NAME = 'SimplyTestable\WebClientBundle\Entity\Test\Test';      
    
    /**
     *
     * @var Logger
     */
    private $logger;     
    
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
     * @var \SimplyTestable\WebClientBundle\Services\TaskService
     */
    private $taskService;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\RemoteTestService
     */
    private $remoteTestService;


    /**
     * @var string[]
     */
    private $finishedStates = array(
        'cancelled',
        'completed',
        'failed-no-sitemap',
    );
    
    
    /**
     *
     * @var Test
     */
    private $test = null;
    
    
    public function __construct(
        EntityManager $entityManager,
        Logger $logger,
        \SimplyTestable\WebClientBundle\Services\TaskService $taskService,
        \SimplyTestable\WebClientBundle\Services\RemoteTestService $remoteTestService            
    ) {
        $this->entityManager = $entityManager; 
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->remoteTestService = $remoteTestService;
    }
    
    
    /**
     * 
     * @return Test
     */
    private function getTest() {
        return $this->test;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\RemoteTestService
     */
    public function getRemoteTestService() {
        return $this->remoteTestService;
    }
    
    
    public function persist(Test $test) {
        $this->entityManager->persist($test);
        $this->entityManager->flush();
    }
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return boolean
     */
    public function has($canonicalUrl, $testId) {        
        if ($this->hasEntity($testId)) {
            return true;
        }

        //try {
            return $this->get($canonicalUrl, $testId) instanceof Test;
        //} catch (WebResourceException $webResourceException) {
//            if ($webResourceException->getCode() == 403) {
//                return false;
//            }
//
//            throw $webResourceException;
        //}
        

    }
    
    
    /**
     *
     * @param string $canonicalUrl
     * @param int $testId
     * @return Test
     */
    public function get($canonicalUrl, $testId) {
        if ($this->hasEntity($testId)) {                  
            /* @var $test Test */
            $this->test = $this->fetchEntity($testId);          
            $this->getRemoteTestService()->setTest($this->getTest());
            
            if (!in_array($this->getTest()->getState() , array('completed', 'rejected'))) {              
                $this->update();             
            }          
        } else {            
            $this->test = new Test();
            $this->getTest()->setTestId($testId);
            $this->getTest()->setWebsite(new NormalisedUrl($canonicalUrl));            
            $this->getRemoteTestService()->setTest($this->getTest());
            
            if (!$this->create()) {
                return false;
            }
        }
        
        if ($this->getRemoteTestService()->has()) {
            $this->getTest()->setUrlCount($this->getRemoteTestService()->get()->getUrlCount());
        }
                
        $this->entityManager->persist($this->getTest());
        $this->entityManager->flush();
        
        return $this->getTest();
    }
    
    
    /**
     *
     * @param int $testId
     * @return boolean
     */
    private function hasEntity($testId) {        
        return $this->getEntityRepository()->hasByTestId($testId);
    }
    
    
    /**
     *
     * @param int $testId
     * @return type 
     */
    private function fetchEntity($testId) {       
        return $this->getEntityRepository()->findOneBy(array(
            'testId' => $testId
        ));
    }
    
    
    /**
     *
     * @return boolean
     */
    private function create() {        
        $remoteTest = $this->getRemoteTestService()->get();        
        if (!$remoteTest) {
            return false;
        }

        $this->getTest()->setState($remoteTest->getState());
        $this->getTest()->setUser($remoteTest->getUser());
        $this->getTest()->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $this->getTest()->setTestId($remoteTest->getId());
        $this->getTest()->setUrlCount($remoteTest->getUrlCount());        
        $this->getTest()->setType($remoteTest->getType()); 
        
        $this->getTest()->setTaskTypes($remoteTest->getTaskTypes());
        
        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->getTest()->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->getTest()->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }          
        
        return true;
    }
    
    
    /**
     *
     * @return boolean
     */
    private function update() { 
        $remoteTest = $this->getRemoteTestService()->get();
        if (!$remoteTest instanceof RemoteTest) {
            return false;
        }
        
        $this->getTest()->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $this->getTest()->setState($remoteTest->getState());
        $this->getTest()->setUrlCount($remoteTest->getUrlCount()); 
        
        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->getTest()->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->getTest()->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }   
    }
    
    
    /**
     *
     * @return \SimplyTestable\WebClientBundle\Repository\TestRepository
     */
    public function getEntityRepository() {
        if (is_null($this->entityRepository)) {
            $this->entityRepository = $this->entityManager->getRepository(self::ENTITY_NAME);
        }
        
        return $this->entityRepository;
    } 
    
    
    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {        
        return $this->entityManager;
    }
    
    
    /**
     * 
     * @param \SimplyTestable\WebClientBundle\Entity\Test\Test $test
     * @return boolean
     */
    public function isFailed(Test $test) {
        $failedStatePrefix = 'failed';        
        return substr($test->getState(), 0, strlen($failedStatePrefix)) === $failedStatePrefix;
    }


    /**
     * @param Test $test
     * @return bool
     */
    public function isFinished(Test $test) {
        return in_array($test->getState(), $this->finishedStates);
    }
   
    
}