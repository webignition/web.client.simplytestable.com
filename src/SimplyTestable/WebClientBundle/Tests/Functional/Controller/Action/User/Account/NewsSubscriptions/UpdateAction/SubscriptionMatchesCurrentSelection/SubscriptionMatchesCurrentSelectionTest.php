<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\SubscriptionMatchesCurrentSelection;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\ActionTest;


abstract class SubscriptionMatchesCurrentSelectionTest extends ActionTest {

    public function preCall() {
        parent::preCall();

        $user = $this->makeUser();
        $this->getUserService()->setUser($user);

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        if ($this->getRequestIsAnnouncementsSelected()) {
            $recipients = $this->getMailchimpListRecipientsService()->get('announcements');
            $recipients->addRecipient($user->getUsername());

            $entityManager->persist($recipients);
            $entityManager->flush();
        }

        if ($this->getRequestIsUpdatesSelected()) {
            $recipients = $this->getMailchimpListRecipientsService()->get('updates');
            $recipients->addRecipient($user->getUsername());

            $entityManager->persist($recipients);
            $entityManager->flush();
        }
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_newssubscriptions_update' => [
                'announcements' => ($this->getRequestIsAnnouncementsSelected()) ? 'already-subscribed' : 'already-unsubscribed',
                'updates' => ($this->getRequestIsUpdatesSelected()) ? 'already-subscribed' : 'already-unsubscribed'
            ]
        ];
    }


}


