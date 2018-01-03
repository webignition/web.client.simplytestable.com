<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage;
use SimplyTestable\WebClientBundle\Entity\Task\Output;

class JsStaticAnalysisResultParser extends ResultParser
{
    /**
     * {@inheritdoc}
     */
    protected function buildResult()
    {
        $result = new Result();

        $rawOutputObject = json_decode($this->getOutput()->getContent());

        if (empty($rawOutputObject)) {
            return $result;
        }

        if ($this->isFailedOutput($rawOutputObject)) {
            $result->addMessage($this->getMessageFromFailedOutput($rawOutputObject->messages[0]));

            return $result;
        }

        if (!$this->hasErrors($rawOutputObject)) {
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
     * @param $outputMessage
     *
     * @return JsTextFileMessage
     */
    private function getMessageFromFailedOutput($outputMessage)
    {
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setMessage($outputMessage->message);
        $message->setClass($outputMessage->messageId);

        return $message;
    }

    /**
     * @param \stdClass $analysisOutput
     *
     * @return bool
     */
    private function isFailureResult(\stdClass $analysisOutput)
    {
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
    private function isFailedOutput(\stdClass $rawOutputObject) {
        return isset($rawOutputObject->messages) && is_array($rawOutputObject->messages) && $rawOutputObject->messages[0]->type === 'error';
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
     * @param \stdClass $entryObject
     * @param string $context
     *
     * @return JsTextFileMessage
     */
    private function getMessageFromEntryObject(\stdClass $entryObject, $context)
    {
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
     * @param \stdClass $analysisOutput
     *
     * @return JsTextFileMessage
     */
    private function getFailureMEssageFromAnalysisOutput(\stdClass $analysisOutput, $context)
    {
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setContext($context);

        $message->setColumnNumber(0);
        $message->setLineNumber(0);

        $message->setMessage($this->getMessageFromErrorReport($analysisOutput->errorReport));
        $message->setFragment($analysisOutput->errorReport->reason);

        return $message;
    }

    /**
     * @param \stdClass $errorReport
     *
     * @return mixed
     */
    private function getMessageFromErrorReport(\stdClass $errorReport)
    {
        if (isset($errorReport->statusCode)) {
            return $errorReport->statusCode;
        }

        if (isset($errorReport->contentType)) {
            return $errorReport->contentType;
        }

        throw new \RuntimeException('Unexpected failure condition');
    }
}
