<?php

namespace SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput;


class Message {    
    
    /**
     * Line number at which the error occurred
     * 
     * @var int
     */
    private $lineNumber = 0;
    
    /**
     * Column number at which the error occurred
     * 
     * @var int
     */
    private $columnNumber = 0;
    
    
    /**
     * The message raised by the validator
     * 
     * @var string
     */
    private $message = '';
    
    /**
     * Textual identifier for the message
     * Not always wholly useful; is currently 'html5' for /any/ html5 validation message
     * 
     * @var string
     */
    private $messageId = '';
    
    /**
     * Message type
     * 
     * @var string
     */
    private $type = '';
    
    
    /**
     *
     * @param int $lineNumber
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Message 
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
            
    
    /**
     *
     * @param int $columnNumber
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Message 
     */
    public function setColumn($columnNumber) {
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
     * @param string $message
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Message 
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }
    
    
    /**
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }
    
    
    /**
     *
     * @param string $messageId
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Message 
     */
    public function setMessageId($messageId) {
        $this->messageId = $messageId;
        return $this;
    }
    
    
    /**
     *
     * @return string
     */
    public function getMessageId() {
        return $this->messageId;
    }
    
    
    /**
     *
     * @param string $type
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Message 
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    
    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    
    /**
     *
     * @param Message $message
     * @return boolean 
     */
    public function equals(Message $message) {       
        if ($this->getLineNumber() != $message->getLineNumber()) {
            return false;
        }
        
        if ($this->getColumnNumber() != $message->getColumnNumber()) {
            return false;            
        }
        
        if ($this->getMessage() != $message->getMessage()) {
            return false;
        }
        
        if ($this->getMessageId() != $message->getMessageId()) {
            return false;
        }
        
        if ($this->getType() != $message->getType()) {
            return false;
        }
        
        return true;
    }
}