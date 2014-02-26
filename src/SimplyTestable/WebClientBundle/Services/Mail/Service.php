<?php
namespace SimplyTestable\WebClientBundle\Services\Mail;

class Service {
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\Mail\Configuration 
     */
    private $mailConfiguration;
    
    /**
     *
     * @var \MZ\PostmarkBundle\Postmark\Message
     */
    private $postmarkMessage;
    
    
    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\Postmark\Sender 
     */
    private $postmarkSender;

    
    /**
     * 
     * @param \MZ\PostmarkBundle\Postmark\Message $postmarkMessage
     */
    public function __construct(
        \SimplyTestable\WebClientBundle\Services\Mail\Configuration $mailConfiguration,
        \MZ\PostmarkBundle\Postmark\Message $postmarkMessage,
        \SimplyTestable\WebClientBundle\Services\Postmark\Sender $postmarkSender) {
        
        $this->mailConfiguration = $mailConfiguration;        
        $this->postmarkMessage = clone $postmarkMessage;
        $this->postmarkSender = $postmarkSender;
    }

    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\Mail\Configuration
     */
    public function getConfiguration() {
        return $this->mailConfiguration;
    }
    
    
    
    /**
     * 
     * @return \MZ\PostmarkBundle\Postmark\Message
     */
    public function getNewMessage() {
        return clone $this->postmarkMessage;
    }
    
    
    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\Postmark\Sender
     */
    public function getSender() {
        return $this->postmarkSender;
    }    
    
}