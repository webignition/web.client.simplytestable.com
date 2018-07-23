<?php
namespace App\Command\EmailList;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscribeCommand extends AbstractSubscriptionCommand
{
    const NAME = 'simplytestable:emaillist:subscribe';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::NAME)
            ->setDescription('Subscribe a user to a email list')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mailChimpService->subscribe(
            $input->getArgument(self::ARG_LIST_ID),
            $input->getArgument(self::ARG_EMAIL)
        );
    }
}
