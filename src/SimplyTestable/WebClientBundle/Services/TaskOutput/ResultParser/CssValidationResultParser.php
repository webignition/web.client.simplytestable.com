<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

class CssValidationResultParser extends ResultParser {    
    
    /**
     * @return Result
     */
    public function getResult() {        
        $result = new Result();
        
        $rawOutputArray = json_decode($this->getOutput()->getContent());
        
        if (count($rawOutputArray) === 0) {
            return $result;
        }       
   
        foreach ($rawOutputArray as $rawMessageObject) {            
            $result->addMessage($this->getMessageFromOutput($rawMessageObject));
        }
        
        return $result;
    }
    
    
    /**
     *
     * @param \stdClass $rawMessageObject
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage 
     */
    private function getMessageFromOutput(\stdClass $rawMessageObject) {        
        $propertyToMethodMap = array(
            'context' => 'setContext',
            'lineNumber' => 'setLineNumber',
            'message' => 'setMessage',
            'ref' => 'setRef'
        );
        
        $message = new CssTextFileMessage();
        $message->setType('error');
        
        foreach ($propertyToMethodMap as $property => $methodName) {
            if (isset($rawMessageObject->$property)) {
                $message->$methodName($rawMessageObject->$property);
            }
        }
        
        return $message;
    }
    
}