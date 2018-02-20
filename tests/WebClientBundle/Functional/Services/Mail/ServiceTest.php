<?php

namespace Tests\WebClientBundle\Functional\Services\Mail;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class ServiceTest extends AbstractBaseTestCase
{
    /**
     * @var MailService
     */
    private $mailService;

    protected function setUp()
    {
        parent::setUp();

        $this->mailService = $this->container->get('simplytestable.services.mail.service');
    }

    public function testGetConfiguration()
    {
        $this->assertEquals(
            $this->container->get('SimplyTestable\WebClientBundle\Services\Mail\Configuration'),
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
            $this->container->get('SimplyTestable\WebClientBundle\Services\Postmark\Sender'),
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
