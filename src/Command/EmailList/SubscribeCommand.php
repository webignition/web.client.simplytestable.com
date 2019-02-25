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
        $listId = $input->getArgument(self::ARG_LIST_ID) ?? null;
        $email = $input->getArgument(self::ARG_EMAIL) ?? null;

        if (!empty($listId) && !empty($email)) {
            $this->mailChimpService->subscribe($listId, $email);
        }
    }
}
