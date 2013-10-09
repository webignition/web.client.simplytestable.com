<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\LinkIntegrityMessage;

class LinkIntegrityResultParser extends ResultParser {        
    
    /**
     * @return Result
     */
    protected function buildResult() {                                
        $result = new Result();
        
        $rawOutputObject = json_decode($this->getOutput()->getContent());
        
        if (count($rawOutputObject) === 0) {
            return $result;
        }      
        
        foreach ($rawOutputObject as $rawMessageObject) {
            if ($this->isError($rawMessageObject)) {
                $result->addMessage($this->getMessageFromOutput($rawMessageObject));
            }            
        }
        
        return $result;
    }
    
    
    /**
     *
     * @param \stdClass $rawMessageObject
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\TextFileMessage 
     */
    private function getMessageFromOutput(\stdClass $rawMessageObject) {        
        $message = new LinkIntegrityMessage();
        $message->setType('error');
        $message->setMessage(strtoupper($rawMessageObject->type).' error '.$rawMessageObject->state);
        $message->setClass($rawMessageObject->type);
        $message->setContext($rawMessageObject->context);
        $message->setUrl($rawMessageObject->url);
        $message->setState($rawMessageObject->state);
        
        return $message;
    }
    
    
    /**
     * 
     * @param \stdClass $rawMessageObject
     * @return boolean
     */
    private function isError(\stdClass $rawMessageObject) {
        if ($rawMessageObject->type == 'curl') {
            return true;
        }
        
        return in_array(substr($rawMessageObject->state, 0, 1), array('3', '4', '5'));
    }
    
}