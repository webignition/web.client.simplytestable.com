<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use SimplyTestable\WebClientBundle\Exception\JobPrepareException;

class StartNewTestJob extends CommandLineJob {    
    
    const QUEUE_NAME = 'job-start';
    const COMMAND = 'php app/console simplytestable:job:start';
    
    protected function getQueueName() {
        return self::QUEUE_NAME;
    }
    
    protected function getArgumentOrder() {
        return array();
    }
    
    protected function getCommand() {
        return self::COMMAND;
    }
    
    protected function failureHandler($output, $returnValue) {
        throw new JobPrepareException(implode("\n", $output), $returnValue);
    }   
}