<?php

namespace App\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\MailChimp\ListRecipients;
use App\Services\MailChimp\ListRecipientsService;
use App\Event\MailChimp\Event as MailChimpEvent;

class Listener
{
    /**
     * @var ListRecipientsService
     */
    private $mailChimpListRecipientsService;

    /**
     * @var ListRecipients
     */
    private $listRecipients;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ListRecipientsService $mailChimpListRecipientsService
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        ListRecipientsService $mailChimpListRecipientsService,
        EntityManagerInterface $entityManager
    ) {
        $this->mailChimpListRecipientsService = $mailChimpListRecipientsService;
        $this->entityManager = $entityManager;
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onSubscribe(MailChimpEvent $event)
    {
        $eventData = $event->getData();
        $email = $eventData['email'] ?? null;

        if (null !== $email) {
            $listRecipients = $this->getListRecipients($event);
            $listRecipients->addRecipient($email);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onUnsubscribe(MailChimpEvent $event)
    {
        $this->handleRemoveRecipientEvent($event);
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onUpEmail(MailChimpEvent $event)
    {
        $eventData = $event->getData();
        $oldEmail = $eventData['old_email'] ?? null;
        $newEmail = $eventData['new_email'] ?? null;

        if (null !== $oldEmail && null !== $newEmail) {
            $listRecipients = $this->getListRecipients($event);
            $listRecipients->removeRecipient($oldEmail);
            $listRecipients->addRecipient($newEmail);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onCleaned(MailChimpEvent $event)
    {
        $this->handleRemoveRecipientEvent($event);
    }

    /**
     * @param MailChimpEvent $event
     */
    private function handleRemoveRecipientEvent(MailChimpEvent $event)
    {
        $eventData = $event->getData();
        $email = $eventData['email'] ?? null;

        if (null !== $email) {
            $listRecipients = $this->getListRecipients($event);
            $listRecipients->removeRecipient($email);

            $this->entityManager->persist($listRecipients);
            $this->entityManager->flush();
        }
    }

    /**
     * @param MailChimpEvent $event
     *
     * @return null|ListRecipients
     */
    private function getListRecipients(MailChimpEvent $event)
    {
        if (is_null($this->listRecipients)) {
            $this->listRecipients = $this->mailChimpListRecipientsService->get(
                $this->mailChimpListRecipientsService->getListName($event->getListId())
            );
        }

        return $this->listRecipients;
    }
}
