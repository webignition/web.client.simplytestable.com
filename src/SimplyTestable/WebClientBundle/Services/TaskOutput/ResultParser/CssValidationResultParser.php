<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage;

class CssValidationResultParser extends ResultParser
{
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

        foreach ($rawOutput as $rawMessage) {
            $result->addMessage($this->getMessageFromOutput($rawMessage));
        }

        return $result;
    }

    /**
     * @param array $rawOutputObject
     *
     * @return bool
     */
    private function isFailedOutput($rawOutputObject)
    {
        if (!isset($rawOutputObject['messages'])) {
            return false;
        }

        return $rawOutputObject['messages'][0]['type'] === 'error';
    }

    /**
     * @param array $messageData
     *
     * @return CssTextFileMessage
     */
    private function getMessageFromFailedOutput($messageData)
    {
        if (!isset($messageData['class']) && isset($messageData['messageId'])) {
            $messageData['class'] = $messageData['messageId'];
        }

        unset($messageData['line_number']);
        unset($messageData['ref']);

        return $this->getMessageFromOutput($messageData);
    }

    /**
     * @param array $messageData
     *
     * @return CssTextFileMessage
     */
    private function getMessageFromOutput($messageData)
    {
        $message = new CssTextFileMessage();

        $message->setType($messageData['type']);

        if (isset($messageData['context'])) {
            $message->setContext($messageData['context']);
        }

        if (isset($messageData['line_number'])) {
            $message->setLineNumber($messageData['line_number']);
        }

        if (isset($messageData['message'])) {
            $message->setMessage($this->sanitizeMessage($messageData['message']));
        }

        if (isset($messageData['ref'])) {
            $message->setRef($messageData['ref']);
        }

        if (isset($messageData['class'])) {
            $message->setClass($messageData['class']);
        }

        return $message;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function sanitizeMessage($message)
    {
        $message = $this->removeUnnecessaryPropertyReference($message);

        return $message;
    }

    /**
     * @param string $message
     *
     * @return string
     */
    private function removeUnnecessaryPropertyReference($message)
    {
        $message = preg_replace('/\(null[^.]+\.html[^)]+\)/', '', $message);

        return $message;
    }
}
