<?php

namespace App\Command\EmailList;

use Doctrine\ORM\EntityManagerInterface;
use App\Services\MailChimp\ListRecipientsService;
use App\Services\MailChimp\Service as MailChimpService;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractRetrieveRecipientsCommand extends AbstractEmailListCommand
{
    protected $listRecipientsService;
    protected $entityManager;

    public function __construct(
        ListRecipientsService $listRecipientsService,
        EntityManagerInterface $entityManager,
        MailChimpService $mailChimpService,
        ?string $name = null
    ) {
        parent::__construct($mailChimpService, $name);

        $this->listRecipientsService = $listRecipientsService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $listName
     *
     * @return string[]
     */
    protected function retrieveAndStoreMemberEmails(string $listName): array
    {
        $listRecipients = $this->listRecipientsService->get($listName);
        $listRecipients->setRecipients([]);

        $memberEmails = $this->mailChimpService->retrieveMemberEmails($listName);

        foreach ($memberEmails as $memberEmail) {
            $listRecipients->addRecipient($memberEmail);
        }

        $this->entityManager->persist($listRecipients);
        $this->entityManager->flush();

        return $memberEmails;
    }

    protected function processListRetrieval(string $listName, OutputInterface $output)
    {
        $output->write(sprintf(
            '<info>Getting recipients for</info> <comment>%s</comment> ... ',
            $listName
        ));

        $memberEmails = $this->retrieveAndStoreMemberEmails($listName);

        $output->writeln(sprintf(
            '%s recipients retrieved',
            count($memberEmails)
        ));
    }
}
