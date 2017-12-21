<?php
namespace SimplyTestable\WebClientBundle\Services\Postmark;

use SimplyTestable\WebClientBundle\Model\Postmark\Response as PostmarkResponse;
use SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception as PostmarkResponseException;

class Sender {

    private $lastMessage;
    private $lastResponse;


    /**
     * @param \MZ\PostmarkBundle\Postmark\Message $message
     * @return PostmarkResponse
     * @throws \SimplyTestable\WebClientBundle\Exception\Postmark\Response\Exception
     */
    public function send(\MZ\PostmarkBundle\Postmark\Message $message) {
        $response = new PostmarkResponse($this->getJsonRespnse($message));

        $this->lastMessage = $message;
        $this->lastResponse = $response;

        if ($response->isError()) {
            throw new PostmarkResponseException($response->getMessage(), $response->getErrorCode());
        }

        return $response;
    }

    /**
     *
     * @return \MZ\PostmarkBundle\Postmark\Message
     */
    public function getLastMessage() {
        return $this->lastMessage;
    }


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Model\Postmark\Response
     */
    public function getLastResponse() {
        return $this->lastResponse;
    }


    /**
     *
     * @param \MZ\PostmarkBundle\Postmark\Message $message
     * @return string
     */
    protected function getJsonRespnse(\MZ\PostmarkBundle\Postmark\Message $message) {
        return $message->send();
    }

}