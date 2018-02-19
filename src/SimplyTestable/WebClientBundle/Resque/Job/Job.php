<?php

namespace SimplyTestable\WebClientBundle\Resque\Job;

use ResqueBundle\Resque\ContainerAwareJob as BaseJob;

abstract class Job extends BaseJob
{
    /**
     * @return string
     */
    abstract protected function getQueueName();

    /**
     * @param array $args
     */
    public function __construct($args = [])
    {
        parent::__construct($args);

        $this->args = $args;
        $this->setQueue($this->getQueueName());
    }

    /**
     * @param string $queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }
}
