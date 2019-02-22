<?php

namespace App\Command\EmailList;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class RetrieveListRecipientsCommand extends AbstractRetrieveRecipientsCommand
{
    const NAME = 'simplytestable:emaillist:retrieve:list';
    const ARG_LIST_NAME = 'listName';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Retrieve recipients for a given list')
            ->addArgument('listName', InputArgument::OPTIONAL, 'name of list to retrieve')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listName = $this->getListName($input, $output);

        if (!($this->listRecipientsService->hasListIdentifier($listName))) {
            return 0;
        }

        $this->processListRetrieval($listName, $output);

        return 0;
    }

    private function getListName(InputInterface $input, OutputInterface $output): string
    {
        $listName = $input->getArgument(self::ARG_LIST_NAME);

        if (empty($listName)) {
            /* @var QuestionHelper $questionHelper */
            $questionHelper = $this->getHelper('question');

            $question = new ChoiceQuestion(
                'Which list do you want to update?',
                $this->listRecipientsService->getListNames()
            );

            $question->setErrorMessage('List name "%s" is not valid');

            $listName = $questionHelper->ask($input, $output, $question);
        }

        return $listName;
    }
}
