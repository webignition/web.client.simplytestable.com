<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class HtmlTextFileMessage extends TextFileMessage {
   
    
    /**
     *
     * @var int
     */
    private $columnNumber;
    
    /**
     *
     * @param int $columnNumber
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\HtmlTextFileMessage
     */
    public function setColumnNumber($columnNumber) {
        $this->columnNumber = $columnNumber;
        return $this;
    }
    
    
    /**
     *
     * @return int 
     */
    public function getColumnNumber() {
        return $this->columnNumber;
    }        
    
}