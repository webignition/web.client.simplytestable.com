<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Entity\Task\Task;
use SimplyTestable\WebClientBundle\Entity\TimePeriod;
use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Exception\UserServiceException;
use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use SimplyTestable\WebClientBundle\Model\RemoteTest\RemoteTest;
use SimplyTestable\WebClientBundle\Repository\TestRepository;
use webignition\NormalisedUrl\NormalisedUrl;

class TestService
{
    const STATE_STARTING = 'new';
    const STATE_CANCELLED = 'cancelled';
    const STATE_COMPLETED = 'completed';
    const STATE_IN_PROGRESS = 'in-progress';
    const STATE_PREPARING = 'preparing';
    const STATE_QUEUED = 'queued';
    const STATE_FAILED_NO_SITEMAP = 'failed-no-sitemap';
    const STATE_REJECTED = 'rejected';
    const STATE_RESOLVING = 'resolving';
    const STATE_RESOLVED = 'resolved';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TestRepository
     */
    private $entityRepository;

    /**
     * @var TaskService
     */
    private $taskService;

    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @var string[]
     */
    private $finishedStates = array(
        self::STATE_REJECTED,
        self::STATE_CANCELLED,
        self::STATE_COMPLETED,
        self::STATE_FAILED_NO_SITEMAP,
    );

    /**
     * @var Test
     */
    private $test = null;

    /**
     * @param EntityManager $entityManager
     * @param LoggerInterface $logger
     * @param TaskService $taskService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        EntityManager $entityManager,
        LoggerInterface $logger,
        TaskService $taskService,
        RemoteTestService $remoteTestService
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->remoteTestService = $remoteTestService;
    }

    /**
     * @return Test
     */
    private function getTest()
    {
        return $this->test;
    }

    /**
     * @return RemoteTestService
     */
    public function getRemoteTestService()
    {
        return $this->remoteTestService;
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
            $this->entityRepository = $this->entityManager->getRepository(Test::class);
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