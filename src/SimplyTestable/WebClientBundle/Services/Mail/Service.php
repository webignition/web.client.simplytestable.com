<?php

namespace SimplyTestable\WebClientBundle\Services\Mail;

use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Services\Configuration\MailConfiguration;
use SimplyTestable\WebClientBundle\Services\PostmarkSender;

class Service
{
    /**
     * @var MailConfiguration
     */
    private $mailConfiguration;

    /**
     *
     * @var PostmarkMessage
     */
    private $postmarkMessage;

    /**
     *
     * @var PostmarkSender
     */
    private $postmarkSender;

    /**
     * @param MailConfiguration $mailConfiguration
     * @param PostmarkMessage $postmarkMessage
     * @param PostmarkSender $postmarkSender
     */
    public function __construct(
        MailConfiguration $mailConfiguration,
        PostmarkMessage $postmarkMessage,
        PostmarkSender $postmarkSender
    ) {

        $this->mailConfiguration = $mailConfiguration;
        $this->postmarkMessage = clone $postmarkMessage;
        $this->postmarkSender = $postmarkSender;
    }

    /**
     * @return MailConfiguration
     */
    public function getConfiguration()
    {
        return $this->mailConfiguration;
    }

    /**
     * @return PostmarkMessage
     */
    public function getNewMessage()
    {
        return clone $this->postmarkMessage;
    }

    /**
     * @return PostmarkSender
     */
    public function getSender()
    {
        return $this->postmarkSender;
    }

    /**
     * @param PostmarkMessage $message
     */
    public function setPostmarkMessage(PostmarkMessage $message)
    {
        $this->postmarkMessage = $message;
    }
}
