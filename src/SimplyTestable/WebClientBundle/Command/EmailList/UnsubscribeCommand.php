<?php
namespace SimplyTestable\WebClientBundle\Command\EmailList;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use SimplyTestable\WebClientBundle\Services\MailChimp\Service as MailChimpService;

class UnsubscribeCommand extends Command
{
    const NAME = 'simplytestable:emaillist:unsubscribe';
    const ARG_LIST_ID = 'listId';
    const ARG_EMAIL = 'email';

    /**
     * @var MailChimpService
     */
    private $mailChimpService;

    /**
     * @param MailChimpService $mailChimpService
     * @param string|null $name
     */
    public function __construct(
        MailChimpService $mailChimpService,
        $name = null
    ) {
        parent::__construct($name);

        $this->mailChimpService = $mailChimpService;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Unsubscribe a user from a email list')
            ->addArgument(self::ARG_LIST_ID, InputArgument::REQUIRED, 'id of list to unsubscribe from')
            ->addArgument(self::ARG_EMAIL, InputArgument::REQUIRED, 'email of user to unsubscribe')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mailChimpService->unsubscribe(
            $input->getArgument(self::ARG_LIST_ID),
            $input->getArgument(self::ARG_EMAIL)
        );
    }
}
