<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class CssTextFileMessage extends TextFileMessage {
    
    /**
     *
     * @var string
     */
    private $context;
    
    
    /**
     *
     * @var string
     */
    private $ref;    
    
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
     * @param string $ref
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage
     */
    public function setRef($ref) {
        $this->ref = $ref;
        return $this;
    }
  
    
    
    /**
     *
     * @return string 
     */    
    public function getRef() {
        return $this->ref;
    }      
    
}