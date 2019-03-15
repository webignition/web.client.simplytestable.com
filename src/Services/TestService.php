<?php

namespace App\Services;

use App\Exception\InvalidContentTypeException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
use App\Entity\Test;
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
     * @var string[]
     */
    private $preparedStates = [
        self::STATE_QUEUED,
        self::STATE_IN_PROGRESS,
        self::STATE_COMPLETED,
        self::STATE_CANCELLED,
    ];

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
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function get(string $canonicalUrl, int $testId): ?Test
    {
        $test = $this->testRepository->findOneBy([
            'testId' => $testId
        ]);

        if (empty($test)) {
            $test = Test::create((int) $testId);
        }

        try {
            $remoteTest = $this->remoteTestService->get($test->getTestId());
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return null;
        }

        if (!$remoteTest instanceof RemoteTest) {
            return null;
        }

        $this->hydrateFromRemoteTest($test, $remoteTest);

        if (in_array($test->getState(), $this->preparedStates) && !$test->hasTaskIds()) {
            $remoteTaskIds = $this->taskService->retrieveRemoteTaskIds($test);
            $test->setTaskIdCollection(implode(',', $remoteTaskIds));
        }

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
