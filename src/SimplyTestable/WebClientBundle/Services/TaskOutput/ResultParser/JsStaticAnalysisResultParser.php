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
        if (is_array($rawOutputObject) || is_null($rawOutputObject) || !$this->hasErrors($rawOutputObject)) {
            return $result;
        }
        
        foreach ($rawOutputObject as $jsSourceReference => $analysisOutput) {            
            $context = ($this->isInlineJsOutputKey($jsSourceReference)) ? 'inline' : $jsSourceReference;
            
            if ($this->hasResultEntries($analysisOutput)) {
                foreach ($analysisOutput->entries as $entryObject) {                
                    $result->addMessage($this->getMessageFromEntryObject($entryObject, $context));
                }                
            } else {
                if ($this->isFailureResult($analysisOutput)) {
                    $result->addMessage($this->getFailureMEssageFromAnalysisOutput($analysisOutput, $context));                 
                }                
            }
            

        }
        
        return $result;
    }
    
    
    /**
     * 
     * @param \stdClass $analysisOutput
     * @return boolean
     */
    private function isFailureResult(\stdClass $analysisOutput) {
        if (!isset($analysisOutput->statusLine)) {
            return false;
        }
        
        return $analysisOutput->statusLine == 'failed';
    }
    
    
    /**
     * 
     * @param \stdClass $analysisOutput
     * @return boolean
     */
    private function hasResultEntries(\stdClass $analysisOutput) {
        return isset($analysisOutput->entries);
    }
    
    
    /**
     * 
     * @param \stdClass $rawOutputObject
     * @return boolean
     */
    private function hasErrors(\stdClass $rawOutputObject) {        
        foreach ($rawOutputObject as $jsSourceReference => $entriesObject) {
            if (isset($entriesObject->statusLine) && $entriesObject->statusLine == 'failed') {
                return true;
            }
            
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
    
    
    /**
     * 
     * @param \stdClass $analysisOutput
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage
     */
    private function getFailureMEssageFromAnalysisOutput(\stdClass $analysisOutput, $context) {        
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setContext($context);
        
        $message->setColumnNumber(0);
        $message->setLineNumber(0);
        $message->setMessage($analysisOutput->errorReport->statusCode);
        $message->setFragment($analysisOutput->errorReport->reason);
        
        return $message;        
    }
    
}