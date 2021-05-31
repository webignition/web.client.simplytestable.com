<?php

namespace App\Command\EmailList;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RetrieveAllRecipientsCommand extends AbstractRetrieveRecipientsCommand
{
    const NAME = 'simplytestable:emaillist:retrieve:all';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Retrieve recipients for all lists')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listNames = $this->listRecipientsService->getListNames();

        foreach ($listNames as $listName) {
            $this->processListRetrieval($listName, $output);
        }

        return 0;
    }
}
