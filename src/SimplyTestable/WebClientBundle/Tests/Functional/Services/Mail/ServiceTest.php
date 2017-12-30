<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\Mail;

use ReflectionClass;
use SimplyTestable\WebClientBundle\Services\Mail\Service as MailService;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;
use MZ\PostmarkBundle\Postmark\Message as PostmarkMessage;

class ServiceTest extends BaseSimplyTestableTestCase
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
            $this->container->get('simplytestable.services.mail.configuration'),
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
            $this->container->get('simplytestable.services.postmark.sender'),
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
