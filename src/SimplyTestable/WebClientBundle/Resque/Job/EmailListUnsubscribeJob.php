<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use SimplyTestable\WebClientBundle\Command\EmailList\UnsubscribeCommand;

class EmailListUnsubscribeJob extends CommandJob {

    const QUEUE_NAME = 'email-list-unsubscribe';

    protected function getQueueName() {
        return self::QUEUE_NAME;
    }

    protected function getCommand() {
        return $this->getContainer()->get(UnsubscribeCommand::class);
    }

    protected function getCommandArgs() {
        return [
            'listId' => $this->args['listId'],
            'email' => $this->args['email']
        ];
    }
}