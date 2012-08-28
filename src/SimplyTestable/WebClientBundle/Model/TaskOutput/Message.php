<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

abstract class Message {
    
    const TYPE_ERROR = 'error';
    const TYPE_NOTICE = 'notice';    
    
    /**
     *
     * @var string
     */
    private $message = '';
    
    
    /**
     *
     * @var string
     */
    private $type = '';
    

    /**
     *
     * @param string $message
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\Message 
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
     * @param string $type
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\Message 
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
     * @return string
     */
    public function __toString() {
        return $this->getMessage();
    } 
    
    
}