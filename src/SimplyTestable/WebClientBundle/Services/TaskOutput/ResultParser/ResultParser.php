<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

abstract class ResultParser
{
    /**
     * @var array
     */
    private $parsedResultCache = [];

    /**
     * @var Output
     */
    private $output;

    /**
     * @return Result
     */
    abstract protected function buildResult();

    /**
     * @return Result
     */
    public function getResult()
    {
        $cacheKey = md5($this->getOutput()->getContent());

        if (!isset($this->parsedResultCache[$cacheKey])) {
            $this->parsedResultCache[$cacheKey] = $this->buildResult();
        }

        return $this->parsedResultCache[$cacheKey];
    }

    /**
     * @param Output $output
     */
    public function setOutput(Output $output)
    {
        $this->output = $output;
    }

    /**
     * @return Output
     */
    protected function getOutput()
    {
        return $this->output;
    }
}
