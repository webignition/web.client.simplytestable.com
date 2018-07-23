<?php

namespace App\Command\CacheValidator;

use App\Services\CacheValidatorHeadersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    const NAME = 'simplytestable:cachevalidator:clear';
    const DEFAULT_LIMIT = 100;

    /**
     * @var CacheValidatorHeadersService
     */
    private $cacheValidatorHeadersService;

    /**
     * @param CacheValidatorHeadersService $cacheValidatorHeadersService
     * @param string|null $name
     */
    public function __construct(CacheValidatorHeadersService $cacheValidatorHeadersService, $name = null)
    {
        parent::__construct($name);

        $this->cacheValidatorHeadersService = $cacheValidatorHeadersService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Clear cache validator headers')
            ->addArgument(
                'limit',
                InputArgument::OPTIONAL,
                '',
                self::DEFAULT_LIMIT
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $this->getLimit($input);
        $count = $this->cacheValidatorHeadersService->count();

        $output->writeln([
            '<info>Clearing cache validator headers</info>',
            sprintf('<comment>%s</comment> items to delete', $this->cacheValidatorHeadersService->count()),
            sprintf('<comment>%s</comment> items per batch', $limit),
            ''
        ]);

        while ($count > 0) {
            $output->writeln(sprintf(
                'Deleting up to <comment>%s</comment> of <comment>%s</comment>',
                $limit,
                $count
            ));

            $this->cacheValidatorHeadersService->clear($limit);
            $count = $this->cacheValidatorHeadersService->count();
        }

        $output->writeln([
            '<info>Done!</info>',
        ]);
    }

    /**
     * @param InputInterface $input
     *
     * @return int
     */
    private function getLimit(InputInterface $input)
    {
        $limit = (int)$input->getArgument('limit');

        return ($limit <= 0) ? self::DEFAULT_LIMIT : $limit;
    }
}
