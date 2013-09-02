<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use SimplyTestable\WebClientBundle\Exception\EmailListSubscribeException;

class EmailListSubscribeJob extends CommandLineJob {    
    
    const QUEUE_NAME = 'email-list-subscribe';
    const COMMAND = 'php app/console simplytestable:emaillist:subscribe';
    
    protected function getQueueName() {
        return self::QUEUE_NAME;
    }
    
    protected function getArgumentOrder() {
        return array('listId', 'email');
    }
    
    protected function getCommand() {
        return self::COMMAND;
    }
    
    protected function failureHandler($output, $returnValue) {
        throw new EmailListSubscribeException(implode("\n", $output), $returnValue);
    }   
}