<?php

namespace SimplyTestable\WebClientBundle\Services\Mail;

use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Services\Postmark\Sender as PostmarkSender;

class Service
{
    /**
     * @var Configuration
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
     * @param Configuration $mailConfiguration
     * @param PostmarkMessage $postmarkMessage
     * @param PostmarkSender $postmarkSender
     */
    public function __construct(
        Configuration $mailConfiguration,
        PostmarkMessage $postmarkMessage,
        PostmarkSender $postmarkSender
    ) {

        $this->mailConfiguration = $mailConfiguration;
        $this->postmarkMessage = clone $postmarkMessage;
        $this->postmarkSender = $postmarkSender;
    }

    /**
     * @return Configuration
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
}
