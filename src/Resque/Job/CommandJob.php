<?php

namespace App\Resque\Job;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

abstract class CommandJob extends Job
{
    /**
     * @return string
     */
    abstract protected function getCommandName();

    /**
     * Get the arguments required by the to-be-run command.
     *
     * This may differ from the arguments passed to this job, specifically when being run via resque as some additional
     * container-relevant args will be added to the job that are not relevant to the command.
     *
     * return array
     */
    abstract protected function getCommandArgs();

    /**
     * @return string
     */
    abstract protected function getIdentifier();

    public function run($args)
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array_merge([
            'command' => $this->getCommandName(),
        ], $this->getCommandArgs()));

        $output = new BufferedOutput();
        $returnCode = $application->run($input, $output);

        if ($returnCode === 0) {
            return true;
        }

        return $this->handleNonZeroReturnCode($returnCode, $output);
    }

    /**
     * @param int $returnCode
     * @param BufferedOutput $output
     *
     * @return int
     */
    private function handleNonZeroReturnCode($returnCode, $output)
    {
        $logger = $this->getContainer()->get('logger');

        $logger->error(sprintf(
            '%s: task [%s] returned %s',
            get_class($this),
            $this->getIdentifier(),
            $returnCode
        ));

        $logger->error(sprintf(
            '%s: task [%s] output %s',
            get_class($this),
            $this->getIdentifier(),
            trim($output->fetch())
        ));

        return $returnCode;
    }
}
