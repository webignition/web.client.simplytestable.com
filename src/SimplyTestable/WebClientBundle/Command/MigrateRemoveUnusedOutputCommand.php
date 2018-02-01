<?php
namespace SimplyTestable\WebClientBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Repository\TaskOutputRepository;
use SimplyTestable\WebClientBundle\Repository\TaskRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use SimplyTestable\WebClientBundle\Entity\Task\Task;

class MigrateRemoveUnusedOutputCommand extends BaseCommand
{
    const NAME = 'simplytestable:migrate:remove-unused-output';
    const OPT_LIMIT = 'limit';
    const OPT_DRY_RUN = 'dry-run';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

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
            ->setDescription('Remove output not linked to any task')
            ->addOption('dry-run')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isDryRun = $input->getOption(self::OPT_DRY_RUN);

        $output->writeln('Finding unsued output ...');

        $usedTaskOutputIds = $this->taskRepository->findUsedTaskOutputIds();
        if (empty($usedTaskOutputIds)) {
            $output->writeln('No task outputs found. Done.');

            return 0;
        }

        $unusedTaskOutputIds = $this->taskOutputRepository->findIdsNotIn($usedTaskOutputIds);
        if (empty($unusedTaskOutputIds)) {
            $output->writeln('No unused task outputs found. Done.');

            return 0;
        }

        $output->writeln('['.count($unusedTaskOutputIds).'] outputs found');

        foreach ($unusedTaskOutputIds as $unusedTaskOutputId) {
            $taskOutputToRemove = $this->taskOutputRepository->find($unusedTaskOutputId);

            $output->writeln('Removing output ['.$unusedTaskOutputId.']');

            if (!$isDryRun) {
                $this->entityManager->remove($taskOutputToRemove);
                $this->entityManager->flush();
            }
        }

        return 0;
    }
}
