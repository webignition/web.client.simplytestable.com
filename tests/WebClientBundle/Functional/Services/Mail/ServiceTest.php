<?php

namespace Tests\WebClientBundle\Functional\Services\Mail;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use SimplyTestable\WebClientBundle\Services\PostmarkSender;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;
use SimplyTestable\WebClientBundle\Services\Mail\Configuration as MailConfiguration;

class ServiceTest extends AbstractBaseTestCase
{
    /**
     * @var MailService
     */
    private $mailService;

    protected function setUp()
    {
        parent::setUp();

        $this->mailService = $this->container->get('SimplyTestable\WebClientBundle\Services\Mail\Service');
    }

    public function testGetConfiguration()
    {
        $this->assertEquals(
            $this->container->get(MailConfiguration::class),
            $this->mailService->getConfiguration()
        );
    }

    public function testGetNewMessage()
    {
        $message = $this->mailService->getNewMessage();

        $this->assertInstanceOf(PostmarkMessage::class, $message);
    }

    public function testGetSender()
    {
        $this->assertEquals(
            $this->container->get(PostmarkSender::class),
            $this->mailService->getSender()
        );
    }

    public function testSetPostmarkMessage()
    {
        $from = 'foo@example.com';

        $message = $this->mailService->getNewMessage();
        $message->setFrom($from);

        $this->mailService->setPostmarkMessage($message);

        $newMessage = $this->mailService->getNewMessage();

        $reflectionClass = new ReflectionClass(PostmarkMessage::class);

        $reflectionProperty = $reflectionClass->getProperty('from');
        $reflectionProperty->setAccessible(true);

        $this->assertEquals($from, $reflectionProperty->getValue($newMessage));
    }
}
