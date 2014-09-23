<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use SimplyTestable\WebClientBundle\Command\EmailList\SubscribeCommand;

class EmailListSubscribeJob extends CommandJob {

    const QUEUE_NAME = 'email-list-subscribe';

    protected function getQueueName() {
        return self::QUEUE_NAME;
    }

    protected function getCommand() {
        return new SubscribeCommand();
    }

    protected function getCommandArgs() {
        return [
            'listId' => $this->args['listId'],
            'email' => $this->args['email']
        ];
    }

}