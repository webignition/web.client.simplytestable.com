<?php

namespace App\Services;

use App\Entity\TimePeriod;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
use App\Entity\Test\Test;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Model\RemoteTest\RemoteTest;
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
     * @var EntityRepository
     */
    private $testRepository;

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
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface $logger
     * @param TaskService $taskService
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        TaskService $taskService,
        RemoteTestService $remoteTestService
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->taskService = $taskService;
        $this->remoteTestService = $remoteTestService;

        $this->testRepository = $this->entityManager->getRepository(Test::class);
    }

    /**
     * @param string $canonicalUrl
     * @param int $testId
     *
     * @return Test|null
     *
     * @throws CoreApplicationRequestException
     */
    public function get($canonicalUrl, $testId)
    {
        $test = $this->testRepository->findOneBy([
            'testId' => $testId
        ]);

        if (empty($test)) {
            $test = Test::create((int) $testId, (string) (new NormalisedUrl($canonicalUrl)));
        }

        try {
            $remoteTest = $this->remoteTestService->get($test);
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return null;
        }

        if (!$remoteTest instanceof RemoteTest) {
            return null;
        }

        $this->hydrateFromRemoteTest($test, $remoteTest);

        $this->entityManager->persist($test);
        $this->entityManager->flush();

        return $test;
    }

    private function hydrateFromRemoteTest(Test $test, RemoteTest $remoteTest)
    {
        $test->setUser($remoteTest->getUser());
        $test->setWebsite(new NormalisedUrl($remoteTest->getWebsite()));
        $test->setState($remoteTest->getState());
        $test->setUrlCount($remoteTest->getUrlCount());
        $test->setTestId($remoteTest->getId());
        $test->setType((string) $remoteTest->getType());
        $test->setTaskTypes($remoteTest->getTaskTypes());

        $remoteTimePeriod = $remoteTest->getTimePeriod();

        if (!is_null($remoteTimePeriod)) {
            $test->setTimePeriod(TimePeriod::fromTimePeriod($remoteTimePeriod));
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
