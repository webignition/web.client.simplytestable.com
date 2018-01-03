<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;

class Factory
{
    /**
     * @var array
     */
    private $parsers;

    /**
     * @param $parserConfiguration
     */
    public function __construct($parserConfiguration)
    {
        foreach ($parserConfiguration as $taskType => $parserDetail) {
            $this->parsers[$taskType] = new $parserDetail['class'];
        }
    }

    /**
     * @param Output $output
     *
     * @return ResultParser
     */
    public function getParser(Output $output)
    {
        return $this->parsers[$output->getType()];
    }
}
