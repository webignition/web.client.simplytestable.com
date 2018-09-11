<?php

namespace App\Command\EmailList;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\MailChimp\ListRecipientsService;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Services\MailChimp\Service as MailChimpService;
use Symfony\Component\Console\Question\ChoiceQuestion;

class RetrieveRecipientsCommand extends AbstractEmailListCommand
{
    const NAME = 'simplytestable:emaillist:retrieve-recipients';
    const ARG_LIST_NAME = 'listName';

    /**
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ListRecipientsService $listRecipientsService
     * @param MailChimpService $mailChimpService
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(
        ListRecipientsService $listRecipientsService,
        EntityManagerInterface $entityManager,
        MailChimpService $mailChimpService,
        $name = null
    ) {
        parent::__construct($mailChimpService, $name);

        $this->listRecipientsService = $listRecipientsService;
        $this->entityManager = $entityManager;
    }

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

        $output->write('Getting recipients for "' . $listName . '" ... ');

        $listRecipients = $this->listRecipientsService->get($listName);
        $listRecipients->setRecipients([]);

        $memberEmails = $this->mailChimpService->retrieveMemberEmails($listName);

        $output->writeln(count($memberEmails) . ' recipients retrieved');

        foreach ($memberEmails as $memberEmail) {
            $listRecipients->addRecipient($memberEmail);
        }

        $this->entityManager->persist($listRecipients);
        $this->entityManager->flush();

        return 0;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return string
     */
    private function getListName(InputInterface $input, OutputInterface $output)
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
