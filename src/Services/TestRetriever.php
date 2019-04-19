<?php

namespace App\Services;

use App\Entity\Test as TestEntity;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Model\Test as TestModel;
use App\Exception\InvalidCredentialsException;
use Doctrine\ORM\EntityManagerInterface;

class TestRetriever
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
    const STATE_EXPIRED  = 'expired';

    private $entityManager;
    private $taskService;
    private $remoteTestService;
    private $testFactory;

    /**
     * @var string[]
     */
    private $preparedStates = [
        self::STATE_QUEUED,
        self::STATE_IN_PROGRESS,
        self::STATE_COMPLETED,
        self::STATE_CANCELLED,
        self::STATE_EXPIRED,
    ];

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskService $taskService,
        RemoteTestService $remoteTestService,
        TestFactory $testFactory
    ) {
        $this->entityManager = $entityManager;
        $this->taskService = $taskService;
        $this->remoteTestService = $remoteTestService;
        $this->testFactory = $testFactory;
    }

    /**
     * @param int $testId
     * @return TestModel|null
     * @throws InvalidCredentialsException
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     */
    public function retrieve(int $testId): ?TestModel
    {
        $testRepository = $this->entityManager->getRepository(TestEntity::class);
        $entity = $testRepository->findOneBy([
            'testId' => $testId
        ]);

        if (empty($entity)) {
            $entity = TestEntity::create((int) $testId);
        }

        $remoteTestData = null;

        try {
            $remoteTestData = $this->remoteTestService->getSummaryData($entity->getTestId());
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            return null;
        }

        if (empty($remoteTestData)) {
            return null;
        }

        $testModel = $this->testFactory->create($entity, $remoteTestData);

        if (in_array($testModel->getState(), $this->preparedStates) && empty($testModel->getTaskIds())) {
            $remoteTaskIds = $this->taskService->retrieveRemoteTaskIds($entity->getTestId());
            $entity->setTaskIdCollection(implode(',', $remoteTaskIds));
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $testModel;
    }
}
