<?php

namespace App\Command\PdpPslData;

use App\Services\Pdp\RulesRetriever;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RefreshCommand extends Command
{
    const NAME = 'simplytestable:psppsldata:refresh';
    const RETURN_CODE_OK = 0;
    const RETURN_CODE_FAILURE = 1;

    /**
     * @var RulesRetriever
     */
    private $rulesRetriever;

    /**
     * @param RulesRetriever $rulesRetriever
     * @param string|null $name
     */
    public function __construct(RulesRetriever $rulesRetriever, $name = null)
    {
        parent::__construct($name);

        $this->rulesRetriever = $rulesRetriever;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Refresh locally-cached pdp psl data')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>Retrieving PDP PSL data ...</info>',
            ''
        ]);
        $retrievalResult = $this->rulesRetriever->retrieve();

        if ($retrievalResult === false) {
            $output->writeln([
                '<error>Failed to retrieve</error>'
            ]);

            return self::RETURN_CODE_FAILURE;
        }

        $output->writeln([
            sprintf(
                '<comment>Retrieved %s bytes</comment>',
                $retrievalResult
            )
        ]);

        return self::RETURN_CODE_OK;
    }
}
