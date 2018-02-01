<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

interface ResultParserInterface
{
    /**
     * @return Result
     */
    public function getResult();

    /**
     * @param Output $output
     */
    public function setOutput(Output $output);

    /**
     * @param string $taskType
     *
     * @return bool
     */
    public function handles($taskType);
}
