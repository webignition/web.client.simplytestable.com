<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\HtmlTextFileMessage;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

class HtmlValidationResultParser extends ResultParser {        
    
    /**
     * @return Result
     */
    protected function buildResult() {        
        $result = new Result();
        
        $rawOutputObject = json_decode($this->getOutput()->getContent());
        
        if (!isset($rawOutputObject->messages)) {
            return $result;
        }      
        
        foreach ($rawOutputObject->messages as $rawMessageObject) {
            $result->addMessage($this->getMessageFromOutput($rawMessageObject));
        }
        
        return $result;
    }
    
    
    /**
     *
     * @param \stdClass $rawMessageObject
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\TextFileMessage 
     */
    private function getMessageFromOutput(\stdClass $rawMessageObject) {
        $propertyToMethodMap = array(
            'lastColumn' => 'setColumnNumber',
            'lastLine' => 'setLineNumber',
            'message' => 'setMessage',
            'messageId' => 'setClass'
        );
        
        $message = new HtmlTextFileMessage();
        $message->setType($rawMessageObject->type);
        
        foreach ($propertyToMethodMap as $property => $methodName) {
            if (isset($rawMessageObject->$property)) {
                $message->$methodName($rawMessageObject->$property);
            }
        }
        
        return $message;
    }
    
}