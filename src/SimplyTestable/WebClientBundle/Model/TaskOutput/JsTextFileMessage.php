<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class JsTextFileMessage extends TextFileMessage {
    
    /**
     *
     * @var string
     */
    private $context;
    
    
    /**
     *
     * @var int
     */
    private $columnNumber;
    
    
    /**
     *
     * @var string
     */
    private $fragment;
    
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
    
    /**
     *
     * @param string $context
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage
     */
    public function setContext($context) {
        $this->context = $context;
        return $this;
    }
  
    
    
    /**
     *
     * @return string 
     */    
    public function getContext() {
        return $this->context;
    }      
    
    
    /**
     *
     * @param string $fragment
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage
     */
    public function setFragment($fragment) {
        $this->fragment = $fragment;
        return $this;
    }
  
    
    
    /**
     *
     * @return string 
     */    
    public function getFragment() {
        return $this->fragment;
    }     
    
}