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
    
    
    public function getFirstError() {        
        $errors = $this->getErrors();
        return $errors[0];
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
    
    
    /**
     *
     * @return boolean 
     */
    public function isHttpRedirectLoopFailure() {
        foreach ($this->getErrors() as $error) {
            if ($error->getClass() == 'http-retrieval-redirect-loop') {
                return true;
            }
        }
        
        return false;
    } 
    
    
    /**
     *
     * @return boolean 
     */
    public function isHttpRedirectLimitFailure() {
        foreach ($this->getErrors() as $error) {
            if ($error->getClass() == 'http-retrieval-redirect-limit-reached') {
                return true;
            }
        }
        
        return false;
    }     
    
    
    /**
     *
     * @return boolean 
     */
    public function isCharacterEncodingFailure() {
        foreach ($this->getErrors() as $error) {
            if ($error->getClass() == 'character-encoding') {
                return true;
            }
        }
        
        return false;
    }     
        
    
}