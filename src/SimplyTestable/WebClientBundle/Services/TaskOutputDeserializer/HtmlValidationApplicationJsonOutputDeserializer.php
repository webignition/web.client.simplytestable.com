<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutputDeserializer;

use SimplyTestable\WebClientBundle\Entity\Task\HtmlValidationOutput\Output as HtmlValidationOutput;
use SimplyTestable\WebClientBundle\Entity\Task\HtmlValidationOutput\Message;

class HtmlValidationApplicationJsonOutputDeserializer extends TaskOutputDeserializer {
    
    private $fieldToMethodMap = array(
        'lastColumn' => 'setColumn',
        'lastLine' => 'setLineNumber',
        'message' => 'setMessage',
        'messageid' => 'setMessageId',
        'type' => 'setType',
    );
    
    
    /**
     * @param string $output
     * @return Output
     */
    public function deserialize($output) {        
        $jsonObject = json_decode($output);
        
        $outputObject = new HtmlValidationOutput();
        //$outputObject->setContent($output);
        
        foreach ($jsonObject->messages as $message) {
            $outputMessage = new Message();
            
            foreach ($this->fieldToMethodMap as $fieldName => $methodName) {
                if (isset($message->$fieldName)) {
                    $outputMessage->$methodName($message->$fieldName);
                }
            }
            
            $outputObject->addMessage($outputMessage);
        }
        
        return $outputObject;
    }
    
}