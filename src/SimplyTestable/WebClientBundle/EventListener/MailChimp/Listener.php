<?php

namespace SimplyTestable\WebClientBundle\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SimplyTestable\WebClientBundle\Entity\MailChimp\ListRecipients;
use SimplyTestable\WebClientBundle\Services\MailChimp\ListRecipientsService;
use SimplyTestable\WebClientBundle\Event\MailChimp\Event as MailChimpEvent;

class Listener
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ListRecipientsService
     */
    private $mailChimpListRecipientsService;

    /**
     * @var ListRecipients
     */
    private $listRecipients;

    /**
     * @var MailChimpEvent
     */
    private $event;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param LoggerInterface $logger
     * @param ListRecipientsService $mailChimpListRecipientsService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        LoggerInterface $logger,
        ListRecipientsService $mailChimpListRecipientsService,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->mailChimpListRecipientsService = $mailChimpListRecipientsService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onSubscribe(MailChimpEvent $event)
    {
        $this->event = $event;

        if (array_key_exists('email', $event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->addRecipient($event->getData()['email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onUnsubscribe(MailChimpEvent $event)
    {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onUpEmail(MailChimpEvent $event)
    {
        $this->event = $event;

        if (array_key_exists('old_email', $event->getData()) && array_key_exists('new_email', $event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($event->getData()['old_email']);
            $listRecipients->addRecipient($event->getData()['new_email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onCleaned(MailChimpEvent $event)
    {
        $this->event = $event;
        $this->handleRemoveRecipientEvent();
    }

    /**
     *
     */
    private function handleRemoveRecipientEvent()
    {
        if (array_key_exists('email', $this->event->getData())) {
            $listRecipients = $this->getListRecipients();
            $listRecipients->removeRecipient($this->event->getData()['email']);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @return null|ListRecipients
     */
    private function getListRecipients()
    {
        if (is_null($this->listRecipients)) {
            $this->listRecipients = $this->mailChimpListRecipientsService->get(
                $this->mailChimpListRecipientsService->getListName($this->event->getListId())
            );
        }

        return $this->listRecipients;
    }
}
