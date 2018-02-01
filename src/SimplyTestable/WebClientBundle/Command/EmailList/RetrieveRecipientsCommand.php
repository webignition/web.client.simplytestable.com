<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;

class RetrieveRecipientsCommand extends Command
{
    const NAME = 'simplytestable:emaillist:retrieve-recipients';
    const ARG_LIST_NAME = 'listName';

    /**
     * @var ListRecipientsService
     */
    private $listRecipientsService;

    /**
     * @var MailChimpService
     */
    private $mailChimpService;

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
        MailChimpService $mailChimpService,
        EntityManagerInterface $entityManager,
        $name = null
    ) {
        parent::__construct($name);

        $this->listRecipientsService = $listRecipientsService;
        $this->mailChimpService = $mailChimpService;
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
            ->addArgument('listName', InputArgument::REQUIRED, 'name of list to retrieve')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listName = $input->getArgument(self::ARG_LIST_NAME);

        if (!($this->listRecipientsService->hasListIdentifier($listName))) {
            return 0;
        }

        $output->write('Getting recipients for "' . $listName . '" ... ');

        $listRecipients = $this->listRecipientsService->get($listName);
        $listRecipients->setRecipients([]);

        $members = $this->mailChimpService->retrieveMembers($listName);

        $output->writeln(count($members) . ' recipients retrieved');

        foreach ($members as $member) {
            $listRecipients->addRecipient($member['email']);
        }

        $this->entityManager->persist($listRecipients);
        $this->entityManager->flush();

        return 0;
    }
}
