<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class Result {
    
    /**
     * Collection of Message objects
     * 
     * @var array
     */
    private $messages;
    
    public function __construct() {
        $this->messages = array();
    }
    
    
    /**
     *
     * @param Message $message
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\Result 
     */
    public function addMessage(Message $message) {
        $this->messages[] = $message;
        return $this;
    }
    
    
    /**
     * Get collection of error messages
     *  
     * @return array 
     */    
    public function getErrors() {
        $errors = array();

        foreach ($this->messages as $message) {
            /* @var $message Message */
            if ($message->getType() == Message::TYPE_ERROR) {
                $errors[] = $message;
            }
        }
        
        return $errors;
    }
    
    
    /**
     *
     * @return int
     */
    public function getErrorCount() {
        return count($this->getErrors());
    }
    
    
    /**
     *
     * @return boolean
     */
    public function hasErrors() {
        return $this->getErrorCount() > 0;
    }
        
    
}