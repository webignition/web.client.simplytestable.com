<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use SimplyTestable\WebClientBundle\Command\EmailList\UnsubscribeCommand;

class EmailListUnsubscribeJob extends AbstractEmailListJob
{
    const QUEUE_NAME = 'email-list-unsubscribe';

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
        return UnsubscribeCommand::NAME;
    }
}
