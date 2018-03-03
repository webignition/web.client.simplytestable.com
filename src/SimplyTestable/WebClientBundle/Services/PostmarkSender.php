<?php

namespace SimplyTestable\WebClientBundle\Services;

use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class PostmarkSender
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
        $response = new PostmarkResponse($this->getJsonResponse($message));

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
    private function getJsonResponse(PostmarkMessage $message)
    {
        return $message->send();
    }
}
