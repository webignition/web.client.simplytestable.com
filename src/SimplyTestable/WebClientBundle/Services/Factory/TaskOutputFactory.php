<?php

namespace SimplyTestable\WebClientBundle\Services\Factory;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;

class TaskOutputFactory
{
    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->taskOutputRepository = $entityManager->getRepository(Output::class);
    }

    /**
     * @param string $type
     * @param array $outputData
     *
     * @return Output
     */
    public function create($type, array $outputData)
    {
        $output = new Output();

        $output->setType($type);
        $output->setContent($outputData['output']);
        $output->setErrorCount($outputData['error_count']);
        $output->setWarningCount($outputData['warning_count']);
        $output->generateHash();

        /* @var Output|null $existingOutput */
        $existingOutput = $this->taskOutputRepository->findOneBy([
            'hash' => $output->getHash(),
        ]);

        return empty($existingOutput)
            ? $output
            : $existingOutput;
    }
}
