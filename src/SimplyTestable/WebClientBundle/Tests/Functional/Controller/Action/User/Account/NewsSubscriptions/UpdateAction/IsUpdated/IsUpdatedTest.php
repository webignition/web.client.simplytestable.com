<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\IsUpdated;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\ActionTest;


abstract class IsUpdatedTest extends ActionTest {

    public function preCall() {
        parent::preCall();

        $user = $this->makeUser();
        $this->getUserService()->setUser($user);

        $announcementsRecipients = $this->getMailchimpListRecipientsService()->get('announcements');
        $updatesRecipients = $this->getMailchimpListRecipientsService()->get('updates');

        if ($this->getRequestIsAnnouncementsSelected()) {
            $announcementsRecipients->removeRecipient($user->getUsername());
        } else {
            $announcementsRecipients->addRecipient($user->getUsername());
        }

        if ($this->getRequestIsUpdatesSelected()) {
            $updatesRecipients->removeRecipient($user->getUsername());
        } else {
            $updatesRecipients->addRecipient($user->getUsername());
        }

        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        $entityManager->persist($announcementsRecipients);
        $entityManager->persist($updatesRecipients);
        $entityManager->flush();
    }

    protected function getExpectedFlashValues() {
        return [
            'user_account_newssubscriptions_update' => [
                'announcements' => ($this->getRequestIsAnnouncementsSelected()) ? 'subscribed' : 'unsubscribed',
                'updates' => ($this->getRequestIsUpdatesSelected()) ? 'subscribed' : 'unsubscribed'
            ]
        ];
    }

}


