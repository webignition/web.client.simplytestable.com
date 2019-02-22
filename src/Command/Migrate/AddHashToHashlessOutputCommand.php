<?php

namespace App\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task\Output;
use App\Repository\TaskOutputRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Task\Output as TaskOutput;

class AddHashToHashlessOutputCommand extends AbstractMigrationCommand
{
    const NAME = 'simplytestable:add-hash-to-hashless-output';
    const OPT_LIMIT = 'limit';
    const OPT_DRY_RUN = 'dry-run';

    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    public function __construct(EntityManagerInterface $entityManager, ?string $name = null)
    {
        parent::__construct($entityManager, $name);

        $this->taskOutputRepository = $entityManager->getRepository(Output::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Set the hash property on TaskOutput objects that have no hash set')
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

        $output->writeln('Finding hashless output ...');
        $hashlessOutputIds = $this->taskOutputRepository->findHashlessOutputIds($limit);

        if (empty($hashlessOutputIds)) {
            $output->writeln('No task outputs require a hash to be set. Done.');

            return 0;
        }

        $output->writeln(count($hashlessOutputIds).' outputs require a hash to be set.');

        foreach ($hashlessOutputIds as $hashlessOutputId) {
            /* @var TaskOutput $taskOutput */
            $taskOutput = $this->taskOutputRepository->find($hashlessOutputId);

            $output->writeln('Setting hash for [' . $taskOutput->getId() . ']');
            $taskOutput->generateHash();

            if (!$isDryRun) {
                $this->entityManager->persist($taskOutput);
                $this->entityManager->flush();
            }

            $this->entityManager->detach($taskOutput);
        }

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
