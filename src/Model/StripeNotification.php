<?php

namespace App\Model;

class StripeNotification
{
    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $message;

    public function __construct(string $recipient, string $subject, string $message)
    {
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
