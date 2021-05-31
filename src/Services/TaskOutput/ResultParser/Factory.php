<?php

namespace App\Services\TaskOutput\ResultParser;

use App\Entity\Task\Output;

class Factory
{
    /**
     * @var ResultParserInterface[]
     */
    private $parsers;

    /**
     * @param ResultParserInterface $resultParser
     */
    public function addResultParser(ResultParserInterface $resultParser)
    {
        $this->parsers[] = $resultParser;
    }

    /**
     * @param Output $output
     *
     * @return ResultParserInterface
     */
    public function getParser(Output $output)
    {
        foreach ($this->parsers as $parser) {
            if ($parser->handles($output->getType())) {
                return $parser;
            }
        }

        return null;
    }
}
