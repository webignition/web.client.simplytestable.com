<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

class Result {
    
    const OUTCOME_PASSED = 'passed';
    const OUTCOME_FAILED = 'failed';
    
    /**
     * Collection of Message objects
     * 
     * @var array
     */
    private $messages;
    
    
    /**
     *
     * @var string
     */
    private $outcome;
    
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
    
    
    /**
     *
     * @return string
     */
    public function getOutcome() {
        if ($this->hasErrors()) {
            return self::OUTCOME_FAILED;
        }
        
        return self::OUTCOME_PASSED;
    }
        
    
}