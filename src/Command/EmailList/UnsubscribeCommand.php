<?php

namespace App\Command\EmailList;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UnsubscribeCommand extends AbstractSubscriptionCommand
{
    const NAME = 'simplytestable:emaillist:unsubscribe';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::NAME)
            ->setDescription('Unsubscribe a user from a email list')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listId = $this->getListId($input);
        $email = $this->getEmail($input);

        if (!empty($listId) && !empty($email)) {
            $this->mailChimpService->unsubscribe($listId, $email);
        }
    }
}
