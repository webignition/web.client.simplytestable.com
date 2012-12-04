<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

class JsStaticAnalysisResultParser extends ResultParser {    
    
    /**
     * @return Result
     */
    public function getResult() {        
        $result = new Result();
        
        $rawOutputObject = json_decode($this->getOutput()->getContent());
        
        if (!$this->hasErrors($rawOutputObject)) {
            return $result;
        }           
        
        foreach ($rawOutputObject as $jsSourceReference => $entriesObject) {            
            $context = ($this->isInlineJsOutputKey($jsSourceReference)) ? 'inline' : $jsSourceReference;
            
            foreach ($entriesObject->entries as $entryObject) {                
                $result->addMessage($this->getMessageFromEntryObject($entryObject, $context));
            }
        }
        
        return $result;
    }
    
    
    /**
     * 
     * @param \stdClass $rawOutputObject
     * @return boolean
     */
    private function hasErrors(\stdClass $rawOutputObject) {
        foreach ($rawOutputObject as $jsSourceReference => $entriesObject) {            
            if (count($entriesObject->entries)) {
                return true;
            }
        }        
        
        return false;
    }
    
    
    /**
     * 
     * @param string $key
     * @return boolean
     */
    private function isInlineJsOutputKey($key) {
        return preg_match('/[a-f0-9]{32}/i', $key) > 0;
    }
    
    
    /**
     *
     * @param \stdClass $entryObject
     * @param string $context
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage 
     */
    private function getMessageFromEntryObject(\stdClass $entryObject, $context) {        
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setContext($context);
        
        $message->setColumnNumber($entryObject->fragmentLine->columnNumber);
        $message->setLineNumber($entryObject->fragmentLine->lineNumber);
        $message->setMessage($entryObject->headerLine->errorMessage);
        $message->setFragment($entryObject->fragmentLine->fragment);
        
        return $message;
    }
    
}