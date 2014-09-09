<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\SubscriptionMatchesCurrentSelection;

use SimplyTestable\WebClientBundle\Tests\Controller\Action\User\Account\NewsSubscriptions\UpdateAction\ActionTest;


abstract class SubscriptionMatchesCurrentSelectionTest extends ActionTest {

    public function preCall() {
        parent::preCall();

        $user = $this->makeUser();
        $this->getUserService()->setUser($user);

        if ($this->getRequestIsAnnouncementsSelected()) {
            $recipients = $this->getMailchimpListRecipientsService()->get('announcements');
            $recipients->addRecipient($user->getUsername());
            $this->getMailchimpListRecipientsService()->persistAndFlush($recipients);
        }

        if ($this->getRequestIsUpdatesSelected()) {
            $recipients = $this->getMailchimpListRecipientsService()->get('updates');
            $recipients->addRecipient($user->getUsername());
            $this->getMailchimpListRecipientsService()->persistAndFlush($recipients);
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


