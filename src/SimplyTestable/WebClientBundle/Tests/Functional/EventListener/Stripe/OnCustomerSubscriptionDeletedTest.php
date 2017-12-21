<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe;

class OnCustomerSubscriptionDeletedTest extends ListenerTest {

    /**
     *
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.deleted';
    }

    /**
     *
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }



    public function testUserCancelledDuringTrialSingleTrialDayRemaining() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'is_during_trial' => 1,
            'trial_days_remaining' => 1
        ));

        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $lastMessage = $postmarkSender->getLastMessage();
        $lastResponse = $postmarkSender->getLastResponse();

        $this->assertNotNull($lastMessage);
        $this->assertNotNull($lastResponse);
        $this->assertFalse($lastResponse->isError());
        $this->assertNotificationMessageContains('1 day');
    }

    public function testUserCancelledDuringTrialMultipleTrialDaysRemaining() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'is_during_trial' => 1,
            'trial_days_remaining' => 20
        ));

        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $lastMessage = $postmarkSender->getLastMessage();
        $lastResponse = $postmarkSender->getLastResponse();

        $this->assertNotNull($lastMessage);
        $this->assertNotNull($lastResponse);
        $this->assertFalse($lastResponse->isError());
        $this->assertNotificationMessageContains('20 days');
    }


    public function testUserCancelledAfterTrial() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'is_during_trial' => 0
        ));

        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $lastMessage = $postmarkSender->getLastMessage();
        $lastResponse = $postmarkSender->getLastResponse();

        $this->assertNotNull($lastMessage);
        $this->assertNotNull($lastResponse);
        $this->assertFalse($lastResponse->isError());
    }


    public function testSystemCancelledFollowingPaymentFailure() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'system'
        ));

        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $lastMessage = $postmarkSender->getLastMessage();
        $lastResponse = $postmarkSender->getLastResponse();

        $this->assertNotNull($lastMessage);
        $this->assertNotNull($lastResponse);
        $this->assertFalse($lastResponse->isError());
    }


}