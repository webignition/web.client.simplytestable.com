<?php

namespace Tests\WebClientBundle\Functional\Resque\Job;

use SimplyTestable\WebClientBundle\Resque\Job\Job;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Command\Command;

abstract class AbstractJobTest extends AbstractBaseTestCase
{
    /**
     * @param array $args
     * @param string $queue
     * @param Command $command
     *
     * @return Job
     */
    protected function createJob($args, $queue, Command $command)
    {
        $resqueJobFactory = $this->container->get('simplytestable.services.resque.jobfactoryservice');

        $job = $resqueJobFactory->create($queue, $args);

        $job->setKernelOptions([
            'kernel.root_dir' => $this->container->getParameter('kernel.root_dir'),
            'kernel.environment' => $this->container->getParameter('kernel.environment'),
            'command' => $command,
        ]);

        return $job;
    }
}
