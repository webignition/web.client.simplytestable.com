<?php

namespace SimplyTestable\WebClientBundle\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface as Logger;

class Listener {

    /**
     *
     * @var Logger
     */
    private $logger;


    /**
     *
     * @var \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService
     */
    private $mailchimpListRecipientsService;


    /**
     *
     * @var \SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients
     */
    private $listRecipients;


    /**
     *
     * @var \SimplyTestable\WebClientBundle\Event\MailChimp\Event
     */
    private $event;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    /**
     *
     * @param Logger $logger
     */
    public function __construct(
        Logger $logger,
        \SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService $mailchimpListRecipientsService,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->mailchimpListRecipientsService = $mailchimpListRecipientsService;
        $this->entityManager = $entityManager;
    }

    // \DomainException
    public function onSubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;

        if (array_key_exists('email', $event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->addRecipient($event->getData()['email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    public function onUnsubscribe(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    }

    public function onUpEmail(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;

        if (array_key_exists('old_email', $event->getData()) && array_key_exists('new_email', $event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($event->getData()['old_email']);
            $listRecipients->addRecipient($event->getData()['new_email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    public function onCleaned(\SimplyTestable\WebClientBundle\Event\MailChimp\Event $event) {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    }


    private function handleRemoveRecipientEvent() {
        if (array_key_exists('email', $this->event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($this->event->getData()['email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }


    private function getListRecipients() {
        if (is_null($this->listRecipients)) {
            $this->listRecipients = $this->mailchimpListRecipientsService->get(
                $this->mailchimpListRecipientsService->getListName($this->event->getListId())
            );
        }

        return $this->listRecipients;
    }

}