<?php
namespace SimplyTestable\WebClientBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Entity\Test\Test;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
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
     * @param string $canonicalUrl
     * @param int $testId
     *
     * @return bool
     *
     * @throws CoreApplicationRequestException
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
     * @param string $canonicalUrl
     * @param int $testId
     *
     * @return Test|bool
     *
     * @throws CoreApplicationRequestException
     */
    public function get($canonicalUrl, $testId)
    {
        $test = $this->entityRepository->findOneBy([
            'testId' => $testId
        ]);

        if (empty($test)) {
            $test = new Test();
            $test->setTestId($testId);
            $test->setWebsite(new NormalisedUrl($canonicalUrl));
        }

        try {
            $this->remoteTestService->setTest($test);
            $remoteTest = $this->remoteTestService->get();
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return false;
        }

        $this->hydrateFromRemoteTest($test, $remoteTest);

        $this->entityManager->persist($test);
        $this->entityManager->flush();

        return $test;
    }

    /**
     * @param Test $test
     * @param RemoteTest $remoteTest
     */
    private function hydrateFromRemoteTest(Test $test, RemoteTest $remoteTest)
    {
        $test->setUser($remoteTest->getUser());
        $test->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $test->setState($remoteTest->getState());
        $test->setUrlCount($remoteTest->getUrlCount());
        $test->setTestId($remoteTest->getId());
        $test->setType($remoteTest->getType());
        $test->setTaskTypes($remoteTest->getTaskTypes());

        $remoteTimePeriod = $remoteTest->getTimePeriod();

        if (!is_null($remoteTimePeriod)) {
            $test->getTimePeriod()->setStartDateTime($remoteTimePeriod->getStartDateTime());
            $test->getTimePeriod()->setEndDateTime($remoteTimePeriod->getEndDateTime());
        }
    }

    /**
     * @param Test $test
     *
     * @return bool
     */
    public function isFinished(Test $test)
    {
        return in_array($test->getState(), $this->finishedStates);
    }
}
