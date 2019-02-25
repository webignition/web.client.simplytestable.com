<?php
namespace App\Command\EmailList;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractSubscriptionCommand extends AbstractEmailListCommand
{
    const ARG_LIST_ID = 'listId';
    const ARG_EMAIL = 'email';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->addArgument(self::ARG_LIST_ID, InputArgument::REQUIRED, 'id of list to subscribe to/unsubscribe from')
            ->addArgument(self::ARG_EMAIL, InputArgument::REQUIRED, 'email of user')
        ;
    }

    protected function getListId(InputInterface $input): ?string
    {
        return $input->getArgument(self::ARG_LIST_ID) ?? null;
    }

    protected function getEmail(InputInterface $input): ?string
    {
        return $input->getArgument(self::ARG_EMAIL) ?? null;
    }
}
