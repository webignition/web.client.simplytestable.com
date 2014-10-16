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
     * @return Message[]
     */    
    public function getErrors() {
        return $this->getMessagesOfType(Message::TYPE_ERROR);
    }
    
    
    /**
     * Get collection of warning messages
     * 
     * @return array
     */
    public function getWarnings() {
        return $this->getMessagesOfType(Message::TYPE_WARNING);
    }
    
    
    private function getMessagesOfType($type) {
        $messages = array();

        foreach ($this->messages as $message) {
            /* @var $message Message */
            if ($message->getType() == $type) {
                $messages[] = $message;
            }
        }
        
        return $messages;          
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
     * @return int
     */
    public function getWarningCount() {
        return count($this->getWarnings());
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
     * @return boolean
     */
    public function hasWarnings() {
        return $this->getWarningCount() > 0;
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
    public function isHtmlMissingDocumentTypeFailure() {
        return $this->isOfErrorClass('/document-type-missing/');         
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHtmlInvalidDocumentTypeFailure() {
        return $this->isOfErrorClass('/document-type-invalid/');         
    }
    
    /**
     * 
     * @return boolean
     */
    public function isMarkuplessTextHtmlFailure() {
        return $this->isOfErrorClass('/document-is-not-markup/');         
    }    
    
    
    /**
     *
     * @return boolean 
     */
    public function isHttpRedirectLoopFailure() {
        return $this->isOfErrorClass('/http-retrieval-redirect-loop/');        
    } 
    
    
    /**
     *
     * @return boolean 
     */
    public function isHttpRedirectLimitFailure() {
        return $this->isOfErrorClass('/http-retrieval-redirect-limit-reached/');       
    }     
    
    
    /**
     *
     * @return boolean 
     */
    public function isCharacterEncodingFailure() {
        return $this->isOfErrorClass('/character-encoding/');
    }  
    
    /**
     * 
     * @return boolean
     */
    public function isCurlTimeoutFailure() {
        return $this->isOfErrorClass('/http-retrieval-curl-code-28/');      
    }
    
    /**
     * 
     * @return boolean
     */
    public function isCurlDnsResolutionFailure() {
        return $this->isOfErrorClass('/http-retrieval-curl-code-6/');      
    }
    
    /**
     * 
     * @return boolean
     */
    public function isCurlUrlFormatFailure() {
        return $this->isOfErrorClass('/http-retrieval-curl-code-3$/');      
    }  
    
    
    /**
     * 
     * @return boolean
     */
    public function isCurlSslFailure() {        
        return $this->isOfErrorClass('/http-retrieval-curl-code-35$/');      
    }  
        
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpClientErrorFailure() {
        return $this->isOfErrorClass('/http-retrieval-4\d\d/');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpServerErrorFailure() {
        return $this->isOfErrorClass('/http-retrieval-5\d\d/');
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isHttpClientOrServerErrorFailure() {
        return $this->isHttpClientErrorFailure() || $this->isHttpServerErrorFailure();
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isValidatorServerErrorFailure() {
        $errors = $this->getErrors();
        if (count($errors) === 0) {
            return false;
        }       
        
        return $errors[0]->getClass() == 'validator-internal-server-error';
    }
    
    
    /**
     * 
     * @return boolean
     */
    public function isCssValidationUnknownExceptionError() {        
        $errors = $this->getErrors();
        if (count($errors) === 0) {
            return false;
        }        
        
        return $errors[0]->getMessage() == 'Unknown error';
    }
    
    /**
     * 
     * @return boolean
     */
    public function isCssValidationSslExceptionError() {        
        $errors = $this->getErrors();
        if (count($errors) === 0) {
            return false;
        }        
        
        return $errors[0]->getMessage() == 'SSL Error';
    }    
    
    
    
    /**
     * 
     * @param string $errorClassPattern
     * @return boolean
     */
    private function isOfErrorClass($errorClassPattern) {
        foreach ($this->getErrors() as $error) {
            if (preg_match($errorClassPattern, $error->getClass()) > 0) {
                return true;
            }
        }
        
        return false;          
    }
        
    
}