<?php
namespace SimplyTestable\WebClientBundle\Command\CacheValidator;

use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    const NAME = 'simplytestable:cachevalidator:clear';

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
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Clearing cache validator headers');
        $this->cacheValidatorHeadersService->clear();
    }
}
