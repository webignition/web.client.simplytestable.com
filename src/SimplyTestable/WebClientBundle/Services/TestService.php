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

        $this->entityRepository = $this->entityManager->getRepository(Test::class);
    }

    /**
     * @return RemoteTestService
     */
    public function getRemoteTestService()
    {
        return $this->remoteTestService;
    }

    /**
     * @param string $canonicalUrl
     * @param int $testId
     *
     * @return bool
     *
     * @throws WebResourceException
     */
    public function has($canonicalUrl, $testId)
    {
        $test = $this->entityRepository->findOneBy([
            'testId' => $testId
        ]);

        if (!empty($test)) {
            return true;
        }

        return $this->get($canonicalUrl, $testId) instanceof Test;
    }

    /**
     * @param $canonicalUrl
     * @param $testId
     *
     * @return bool|Test
     *
     * @throws WebResourceException
     */
    public function get($canonicalUrl, $testId)
    {
        $test = $this->entityRepository->findOneBy([
            'testId' => $testId
        ]);

        if (!empty($test)) {
            $this->test = $test;

            $this->remoteTestService->setTest($this->test);

            if (!in_array($this->test->getState(), [self::STATE_COMPLETED, self::STATE_REJECTED])) {
                $this->update();
            }
        } else {
            $this->test = new Test();
            $this->test->setTestId($testId);
            $this->test->setWebsite(new NormalisedUrl($canonicalUrl));
            $this->remoteTestService->setTest($this->test);

            if (!$this->create()) {
                return false;
            }
        }

        if ($this->remoteTestService->has()) {
            $this->test->setUrlCount($this->remoteTestService->get()->getUrlCount());
        }

        $this->entityManager->persist($this->test);
        $this->entityManager->flush();

        return $this->test;
    }

    /**
     * @return bool
     *
     * @throws WebResourceException
     */
    private function create()
    {
        $remoteTest = $this->remoteTestService->get();
        if (!$remoteTest) {
            return false;
        }

        $this->test->setState($remoteTest->getState());
        $this->test->setUser($remoteTest->getUser());
        $this->test->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $this->test->setTestId($remoteTest->getId());
        $this->test->setUrlCount($remoteTest->getUrlCount());
        $this->test->setType($remoteTest->getType());

        $this->test->setTaskTypes($remoteTest->getTaskTypes());

        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->test->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->test->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }

        return true;
    }

    /**
     * @return bool
     *
     * @throws WebResourceException
     */
    private function update()
    {
        $remoteTest = $this->remoteTestService->get();
        if (!$remoteTest instanceof RemoteTest) {
            return false;
        }

        $this->test->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $this->test->setState($remoteTest->getState());
        $this->test->setUrlCount($remoteTest->getUrlCount());

        $remoteTimePeriod = $remoteTest->getTimePeriod();
        if (!is_null($remoteTimePeriod)) {
            $this->test->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $this->test->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }

        return true;
    }

    /**
     * @return TestRepository
     */
    public function getEntityRepository()
    {
        return $this->entityManager->getRepository(Test::class);
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