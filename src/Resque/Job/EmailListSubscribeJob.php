<?php

namespace App\Resque\Job;

use App\Command\EmailList\SubscribeCommand;

class EmailListSubscribeJob extends AbstractEmailListJob
{
    const QUEUE_NAME = 'email-list-subscribe';

    /**
     * {@inheritdoc}
     */
    protected function getQueueName()
    {
        return self::QUEUE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName()
    {
        return SubscribeCommand::NAME;
    }
}
