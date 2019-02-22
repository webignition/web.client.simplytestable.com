<?php

namespace App\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Repository\TaskOutputRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CanonicaliseTaskOutputCommand extends AbstractMigrationCommand
{
    const NAME = 'simplytestable:migrate:canonicalise-task-output';
    const OPT_LIMIT = 'limit';
    const OPT_DRY_RUN = 'dry-run';

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($entityManager, $name);

        $this->taskRepository = $entityManager->getRepository(Task::class);
        $this->taskOutputRepository = $entityManager->getRepository(Output::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Update tasks to point to canoical output')
            ->addOption(self::OPT_LIMIT)
            ->addOption(self::OPT_DRY_RUN)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isDryRun = $input->getOption(self::OPT_DRY_RUN);
        $limit = $this->getLimit($input);

        $output->writeln('Finding duplicate output ...');

        $duplicateHashes = $this->taskOutputRepository->findDuplicateHashes($limit);

        if (empty($duplicateHashes)) {
            $output->writeln('No duplicate output found. Done.');

            return 0;
        }

        $output->writeln('Processing ' . count($duplicateHashes) . ' duplicate hashes');
        $globalUpdatedTaskCount = 0;

        foreach ($duplicateHashes as $duplicateHash) {
            /* @var Output[] $taskOutputs */
            $taskOutputs = $this->taskOutputRepository->findBy([
                'hash' => $duplicateHash,
            ]);

            $output->writeln('['.(count($taskOutputs) - 1) . '] duplicates found for '.$duplicateHash);

            if (count($taskOutputs) > 1) {
                $sourceOutput = $taskOutputs[0];
                $duplicatesToRemove = array_slice($taskOutputs, 1);
                $updatedTaskCount = 0;

                foreach ($duplicatesToRemove as $taskOutput) {
                    /* @var Task[] $tasks */
                    $tasks = $this->taskRepository->findBy([
                        'output' => $taskOutput,
                    ]);

                    if (count($tasks)) {
                        foreach ($tasks as $task) {
                            $output->writeln('Updating output for task ['.$task->getId().']');
                            $updatedTaskCount++;

                            if (!$isDryRun) {
                                $task->setOutput($sourceOutput);
                                $this->entityManager->persist($task);
                                $this->entityManager->flush();
                            }
                        }

                        if (!$isDryRun) {
                            $this->entityManager->remove($taskOutput);
                            $this->entityManager->flush();
                        }
                    }
                }

                if ($updatedTaskCount === 0) {
                    $output->writeln('No tasks using duplicates of ' . $duplicateHash);
                }

                $globalUpdatedTaskCount += $updatedTaskCount;

                $output->writeln('');
            }
        }

        $output->writeln('['.$globalUpdatedTaskCount.'] tasks updated');

        return 0;
    }

    private function getLimit(InputInterface $input): int
    {
        if (false === $input->getOption(self::OPT_LIMIT)) {
            return 0;
        }

        $limit = filter_var($input->getOption(self::OPT_LIMIT), FILTER_VALIDATE_INT);

        return ($limit <= 0) ? 0 : $limit;
    }
}
