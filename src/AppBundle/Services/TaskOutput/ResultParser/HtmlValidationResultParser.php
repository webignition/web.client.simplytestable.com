<?php

namespace AppBundle\Services\TaskOutput\ResultParser;

use AppBundle\Entity\Task\Output;
use AppBundle\Model\TaskOutput\Result;
use AppBundle\Model\TaskOutput\HtmlTextFileMessage;
use AppBundle\Model\TaskOutput\TextFileMessage;

class HtmlValidationResultParser extends AbstractResultParser
{
    /**
     * @param string $taskType
     *
     * @return bool
     */
    public function handles($taskType)
    {
        return strtolower(Output::TYPE_HTML_VALIDATION) === strtolower($taskType);
    }

    /**
     * {@inheritdoc}
     */
    protected function buildResult()
    {
        $result = new Result();

        $rawOutputObject = json_decode($this->getOutput()->getContent(), true);

        if (!isset($rawOutputObject['messages'])) {
            return $result;
        }

        $messages = $rawOutputObject['messages'];

        if (empty($messages)) {
            return $result;
        }

        foreach ($messages as $rawMessageObject) {
            $result->addMessage($this->getMessageFromOutput($rawMessageObject));
        }

        return $result;
    }

    /**
     * @param array $rawMessageObject
     *
     * @return TextFileMessage
     */
    private function getMessageFromOutput(array $rawMessageObject)
    {
        $message = new HtmlTextFileMessage();

        if (isset($rawMessageObject['lastColumn'])) {
            $message->setColumnNumber($rawMessageObject['lastColumn']);
        }

        if (isset($rawMessageObject['lastLine'])) {
            $message->setLineNumber($rawMessageObject['lastLine']);
        }

        if (isset($rawMessageObject['message'])) {
            $message->setMessage($rawMessageObject['message']);
        }

        if (isset($rawMessageObject['messageId'])) {
            $message->setClass($rawMessageObject['messageId']);
        }

        $message->setType($rawMessageObject['type']);

        return $message;
    }
}
