<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

abstract class ResultParser {
    
    /**
     *
     * @var Output 
     */
    private $output;
    
    
    /**
     *
     * @param Output $output
     * @return \SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory 
     */
    public function setOutput(Output $output) {
        $this->output = $output;
        return $this;
    }
    
    
    /**
     *
     * @return Output
     */
    public function getOutput() {
        return $this->output;
    }
    
    
    /**
     * @return Result
     */
    abstract public function getResult();  
    
}