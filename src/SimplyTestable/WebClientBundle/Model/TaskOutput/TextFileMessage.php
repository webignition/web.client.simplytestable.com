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
     * @var int
     */
    private $columnNumber;
    
    
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
     * @param int $columnNumber
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\TextFileError 
     */
    public function setColumnNumber($columnNumber) {
        $this->columnNumber = $columnNumber;
        return $this;
    }   
    
    
    /**
     *
     * @return int 
     */    
    public function getLineNumber() {
        return $this->lineNumber;
    }
    
    
    /**
     *
     * @return int 
     */
    public function getColumnNumber() {
        return $this->columnNumber;
    }
        
    
}