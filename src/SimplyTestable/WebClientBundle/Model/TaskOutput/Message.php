<?php

namespace SimplyTestable\WebClientBundle\Model\TaskOutput;

abstract class Message {
    
    const TYPE_ERROR = 'error';
    const TYPE_NOTICE = 'notice';    
    const TYPE_WARNING = 'warning'; 
    
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
     * @var string
     */
    private $class = '';
    

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
     * @param string $class
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\Message 
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }    
    
    
    /**
     * 
     * @return string
     */
    public function getClass() {
        return $this->class;
    }      
    
    
    /**
     *
     * @return string
     */
    public function __toString() {
        return $this->getMessage();
    } 
    
    
}