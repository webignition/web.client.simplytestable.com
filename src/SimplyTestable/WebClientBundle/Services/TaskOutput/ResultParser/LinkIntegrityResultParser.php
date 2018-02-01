<?php

namespace SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Model\TaskOutput\LinkIntegrityMessage;

class LinkIntegrityResultParser extends AbstractResultParser
{
    /**
     * @param string $taskType
     *
     * @return bool
     */
    public function handles($taskType)
    {
        return strtolower(Output::TYPE_LINK_INTEGRITY) === strtolower($taskType);
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

        foreach ($rawOutput as $rawMessage) {
            if ($this->isError($rawMessage)) {
                $result->addMessage($this->getMessageFromOutput($rawMessage));
            }
        }

        return $result;
    }

    /**
     * @param array $rawMessage
     *
     * @return LinkIntegrityMessage
     */
    private function getMessageFromOutput($rawMessage)
    {
        $message = new LinkIntegrityMessage();
        $message->setType('error');
        $message->setMessage(strtoupper($rawMessage['type']).' error '.$rawMessage['state']);
        $message->setClass($rawMessage['type']);
        $message->setContext($rawMessage['context']);
        $message->setUrl($rawMessage['url']);
        $message->setState($rawMessage['state']);

        return $message;
    }


    /**
     * @param array $rawMessage
     *
     * @return bool
     */
    private function isError($rawMessage)
    {
        if ($rawMessage['type'] == 'curl') {
            return true;
        }

        return in_array(substr($rawMessage['state'], 0, 1), ['3', '4', '5']);
    }

    /**
     * @param $outputMessage
     *
     * @return LinkIntegrityMessage
     */
    private function getMessageFromFailedOutput($outputMessage)
    {
        $message = new LinkIntegrityMessage();
        $message->setType('error');
        $message->setMessage($outputMessage['message']);
        $message->setClass($outputMessage['messageId']);

        return $message;
    }
}
