<?php
namespace SimplyTestable\WebClientBundle\Command\Migrate;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Output as TaskOutput;

class AddHashToHashlessOutputCommand extends Command
{
    const NAME = 'simplytestable:add-hash-to-hashless-output';
    const OPT_LIMIT = 'limit';
    const OPT_DRY_RUN = 'dry-run';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskOutputRepository
     */
    private $taskOutputRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(EntityManagerInterface $entityManager, $name = null)
    {
        parent::__construct($name);

        $this->entityManager = $entityManager;
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

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function getLimit(InputInterface $input)
    {
        if (false === $input->getOption(self::OPT_LIMIT)) {
            return 0;
        }

        $limit = filter_var($input->getOption(self::OPT_LIMIT), FILTER_VALIDATE_INT);

        return ($limit <= 0) ? 0 : $limit;
    }
}
