<?php

namespace App\Services\TaskOutput\ResultParser;

use App\Model\TaskOutput\Result;
use App\Entity\Task\Output;

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
