<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetCommand extends Command
{
    const NAME = 'simplytestable:reset';
    const RETURN_CODE_OK = 0;
    const RETURN_CODE_FAILURE = 1;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription(
                'Drop database, create database, run database migrations, retrieve email list recipients'
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databaseDropCommand = $this->getApplication()->find('doctrine:database:drop');
        $databaseDropCommand->run(new ArrayInput([
            '--force' => true,
        ]), $output);

        $databaseCreateCommand = $this->getApplication()->find('doctrine:database:create');
        $databaseCreateCommand->run(new ArrayInput([]), $output);

        $databaseMigrationsCommand = $this->getApplication()->find('doctrine:migrations:migrate');
        $databaseMigrationsInput = new ArrayInput([]);
        $databaseMigrationsInput->setInteractive(false);

        $databaseMigrationsCommand->run($databaseMigrationsInput, $output);

        $emailListRecipientsRetrieveAllCommand = $this->getApplication()->find('simplytestable:emaillist:retrieve:all');
        $emailListRecipientsRetrieveAllCommand->run(new ArrayInput([]), $output);

        return self::RETURN_CODE_OK;
    }
}
