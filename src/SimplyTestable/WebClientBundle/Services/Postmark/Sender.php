<?php

namespace SimplyTestable\WebClientBundle\Services\Postmark;

use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class Sender
{
    /**
     * @var PostmarkMessage
     */
    private $lastMessage;

    /**
     * @var PostmarkResponse
     */
    private $lastResponse;

    /**
     * @param PostmarkMessage $message
     *
     * @return PostmarkResponse
     *
     * @throws PostmarkResponseException
     */
    public function send(PostmarkMessage $message)
    {
        $response = new PostmarkResponse($this->getJsonRespnse($message));

        $this->lastMessage = $message;
        $this->lastResponse = $response;

        if ($response->isError()) {
            throw new PostmarkResponseException($response->getMessage(), $response->getErrorCode());
        }

        return $response;
    }

    /**
     * @return PostmarkMessage
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }

    /**
     * @return PostmarkResponse
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param PostmarkMessage $message
     *
     * @return string
     */
    protected function getJsonRespnse(PostmarkMessage $message)
    {
        return $message->send();
    }
}
