<?php

namespace AppBundle\EventListener\MailChimp;

use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\MailChimp\ListRecipients;
use AppBundle\Services\MailChimp\ListRecipientsService;
use AppBundle\Event\MailChimp\Event as MailChimpEvent;

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
        if (array_key_exists('email', $event->getData())) {
            $listRecipients = $this->getListRecipients($event);
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
        $this->handleRemoveRecipientEvent($event);
    }

    /**
     * @param MailChimpEvent $event
     */
    public function onUpEmail(MailChimpEvent $event)
    {
        if (array_key_exists('old_email', $event->getData()) && array_key_exists('new_email', $event->getData())) {
            $listRecipients = $this->getListRecipients($event);
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
        $this->handleRemoveRecipientEvent($event);
    }

    /**
     * @param MailChimpEvent $event
     */
    private function handleRemoveRecipientEvent(MailChimpEvent $event)
    {
        if (array_key_exists('email', $event->getData())) {
            $listRecipients = $this->getListRecipients($event);
            $listRecipients->removeRecipient($event->getData()['email']);

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
