<?php

namespace AppBundle\Services\TaskOutput\ResultParser;

use AppBundle\Model\TaskOutput\Result;
use AppBundle\Entity\Task\Output;

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
