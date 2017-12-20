<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RetrieveRecipientsCommand extends EmailListCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('simplytestable:emaillist:retrieve-recipients')
            ->setDescription('Retrieve recipients for a given list')
            ->addArgument('listName', InputArgument::REQUIRED, 'name of list to retrieve')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailChimpListRecipientsService = $this->getContainer()->get(
            'simplytestable.services.mailchimp.listrecipients'
        );

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        if (!($mailChimpListRecipientsService->hasListIdentifier($input->getArgument('listName')))) {
            return 0;
        }

        $output->write('Getting recipients for "' . $input->getArgument('listName') . '" ... ');

        $listRecipients = $mailChimpListRecipientsService->get($input->getArgument('listName'));
        $listRecipients->setRecipients(array());

        $members = $this->getMailchimpService()->retrieveMembers($input->getArgument('listName'));

        $output->writeln(count($members) . ' recipients retrieved');

        foreach ($members as $member) {
            $listRecipients->addRecipient($member['email']);
        }

        $entityManager->persist($listRecipients);
        $entityManager->flush();

        return 0;
    }
}
