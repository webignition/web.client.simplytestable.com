<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage;

class CssValidationResultParser extends ResultParser
{
    /**
     * @return Result
     */
    protected function buildResult()
    {
        $result = new Result();

        $rawOutputArray = json_decode($this->getOutput()->getContent());

        if (empty($rawOutputArray)) {
            return $result;
        }

        if ($this->isFailedOutput($rawOutputArray)) {
            $result->addMessage($this->getMessageFromFailedOutput($rawOutputArray->messages[0]));

            return $result;
        }

        foreach ($rawOutputArray as $rawMessageObject) {
            $result->addMessage($this->getMessageFromOutput($rawMessageObject));
        }

        return $result;
    }

    private function getMessageFromFailedOutput($outputMessage) {
        $message = new CssTextFileMessage();
        $message->setType('error');
        $message->setMessage($outputMessage->message);

        if (isset($outputMessage->class)) {
            $message->setClass($outputMessage->class);
        } elseif (isset($outputMessage->messageId)) {
            $message->setClass($outputMessage->messageId);
        }

        return $message;
    }

    /**
     *
     * @param \stdClass $rawOutputObject
     * @return boolean
     */
    private function isFailedOutput($rawOutputObject)
    {
        return isset($rawOutputObject->messages) && is_array($rawOutputObject->messages) && $rawOutputObject->messages[0]->type === 'error';
    }


    /**
     *
     * @param \stdClass $rawMessageObject
     * @return \SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage
     */
    private function getMessageFromOutput(\stdClass $rawMessageObject) {
        $propertyToMethodMap = array(
            'context' => 'setContext',
            'line_number' => 'setLineNumber',
            'message' => 'setMessage',
            'ref' => 'setRef'
        );

        $rawMessageObject->message = $this->sanitizeMessage($rawMessageObject->message);

        $message = new CssTextFileMessage();
        $message->setType($rawMessageObject->type);

        foreach ($propertyToMethodMap as $property => $methodName) {
            if (isset($rawMessageObject->$property)) {
                $message->$methodName($rawMessageObject->$property);
            }
        }

        return $message;
    }


    /**
     *
     * @param string $message
     * @return string
     */
    private function sanitizeMessage($message) {
        $message = $this->removeUnnecessaryPropertyReference($message);
        return $message;
    }


    /**
     *
     * @param string $message
     * @return string
     */
    private function removeUnnecessaryPropertyReference($message) {
        $message = preg_replace('/\(null[^.]+\.html[^)]+\)/', '', $message);
        return $message;
    }

}