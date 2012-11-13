<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class TextFileMessage extends Message {
    
    /**
     *
     * @var int
     */
    private $lineNumber;    
    
    /**
     *
     * @param int $lineNumber
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\TextFileError 
     */
    public function setLineNumber($lineNumber) {
        $this->lineNumber = $lineNumber;
        return $this;
    }  
    
    
    /**
     *
     * @return int 
     */    
    public function getLineNumber() {
        return $this->lineNumber;
    }       
    
}