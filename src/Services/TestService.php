<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Test;

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

    private $entityManager;
    private $testRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->testRepository = $this->entityManager->getRepository(Test::class);
    }

    public function getEntity(int $testId): Test
    {
        $test = $this->testRepository->findOneBy([
            'testId' => $testId
        ]);

        if (empty($test)) {
            $test = Test::create((int) $testId);

            $this->entityManager->persist($test);
            $this->entityManager->flush();
        }

        return $test;
    }
}
