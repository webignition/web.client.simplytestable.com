<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage;

class JsStaticAnalysisResultParser extends AbstractResultParser
{
    /**
     * @param string $taskType
     *
     * @return bool
     */
    public function handles($taskType)
    {
        return strtolower(Output::TYPE_JS_STATIC_ANALYSIS) === strtolower($taskType);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResult()
    {
        $result = new Result();

        $rawOutput = json_decode($this->getOutput()->getContent(), true);

        if (empty($rawOutput)) {
            return $result;
        }

        if ($this->isFailedOutput($rawOutput)) {
            $result->addMessage($this->getMessageFromFailedOutput($rawOutput['messages'][0]));

            return $result;
        }

        if (!$this->hasErrors($rawOutput)) {
            return $result;
        }

        foreach ($rawOutput as $jsSourceReference => $analysisOutput) {
            $context = ($this->isInlineJsOutputKey($jsSourceReference)) ? 'inline' : $jsSourceReference;

            if (isset($analysisOutput['entries'])) {
                foreach ($analysisOutput['entries'] as $entry) {
                    $result->addMessage($this->getMessageFromEntryObject($entry, $context));
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
     * @param array $messageData
     *
     * @return JsTextFileMessage
     */
    private function getMessageFromFailedOutput($messageData)
    {
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setMessage($messageData['message']);
        $message->setClass($messageData['messageId']);

        return $message;
    }

    /**
     * @param array $analysisOutput
     *
     * @return bool
     */
    private function isFailureResult($analysisOutput)
    {
        if (!isset($analysisOutput['statusLine'])) {
            return false;
        }

        return $analysisOutput['statusLine'] == 'failed';
    }

    /**
     * @param array $rawOutput
     *
     * @return bool
     */
    private function hasErrors($rawOutput)
    {
        foreach ($rawOutput as $jsSourceReference => $entries) {
            if (isset($entries['statusLine']) && $entries['statusLine'] == 'failed') {
                return true;
            }

            if (count($entries['entries'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isInlineJsOutputKey($key)
    {
        return preg_match('/[a-f0-9]{32}/i', $key) > 0;
    }

    /**
     * @param array $entry
     * @param string $context
     *
     * @return JsTextFileMessage
     */
    private function getMessageFromEntryObject($entry, $context)
    {
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setContext($context);

        $message->setColumnNumber($entry['fragmentLine']['columnNumber']);
        $message->setLineNumber($entry['fragmentLine']['lineNumber']);
        $message->setMessage($entry['headerLine']['errorMessage']);
        $message->setFragment($entry['fragmentLine']['fragment']);

        return $message;
    }

    /**
     * @param array $analysisOutput
     * @param string $context
     *
     * @return JsTextFileMessage
     */
    private function getFailureMessageFromAnalysisOutput($analysisOutput, $context)
    {
        $message = new JsTextFileMessage();
        $message->setType('error');
        $message->setContext($context);

        $message->setColumnNumber(0);
        $message->setLineNumber(0);

        $message->setMessage($this->getMessageFromErrorReport($analysisOutput['errorReport']));
        $message->setFragment($analysisOutput['errorReport']['reason']);

        return $message;
    }

    /**
     * @param array $errorReport
     *
     * @return mixed
     */
    private function getMessageFromErrorReport($errorReport)
    {
        if (isset($errorReport['statusCode'])) {
            return $errorReport['statusCode'];
        }

        if (isset($errorReport['contentType'])) {
            return $errorReport['contentType'];
        }

        throw new \RuntimeException('Unexpected failure condition');
    }
}
