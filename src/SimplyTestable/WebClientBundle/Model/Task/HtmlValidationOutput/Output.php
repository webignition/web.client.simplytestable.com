<?php

namespace SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput;

use SimplyTestable\WebClientBundle\Model\Task\Output as BaseOutput;
use webignition\InternetMediaType\InternetMediaType;
use JMS\SerializerBundle\Annotation as SerializerAnnotation;


/**
 * @SerializerAnnotation\ExclusionPolicy("all") 
 */
class Output extends BaseOutput {
    
    
    /**
     * Collection of Message objects
     * 
     * @var array
     */
    private $messages = array();
    
    
    /**
     *
     * @param Message $message
     * @return \SimplyTestable\WebClientBundle\Model\Task\HtmlValidationOutput\Output 
     */
    public function addMessage(Message $message) {
        if (!$this->contains($message)) {
            $this->messages[] = $message;
        }
        
        return $this;
    }
    
    
    /**
     * Get all messages for this output
     * 
     * @return array
     */
    public function getMessages() {
        return $this->messages;
    }
    
    
    /**
     * Get collection of messages that are errors
     * 
     * @return array
     */
    public function getErrorMessages() {
        $errorMessages = array();
        
        foreach ($this->messages as $message) {
            if ($message->getType() == 'error') {
                $errorMessages[] = $message;
            }            
        }
        
        return $errorMessages;
    }
    
    
    /**
     *
     * @param Message $message
     * @return boolean 
     */
    private function contains(Message $message) {
        foreach ($this->messages as $comparatorMessage) {
            if ($comparatorMessage->equals($message)) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getPublicSerializedContent() {
        return $this->getMessages();
    }
    
}