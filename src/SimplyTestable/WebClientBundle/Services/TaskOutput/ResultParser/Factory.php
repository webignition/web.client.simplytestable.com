<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Message;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Error;

class Factory {
    
    /**
     *
     * @var array 
     */
    private $parsers;
        
    
    public function __construct($parserConfiguration) {
        foreach ($parserConfiguration as $taskType => $parserDetail) {
            $this->parsers[$taskType] = new $parserDetail['class'];
        }
    }
    
    
    /**
     *
     * @return ResultParser 
     */
    public function getParser(Output $output) {
        return $this->parsers[$output->getType()];
    }
    
}