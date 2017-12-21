<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest {

    const AMOUNT = 1900;

    protected function setUp() {
        parent::setUp();

        $this->callListener($this->getListenerData());
    }

    protected function getListenerData() {
        return [
            'has_card' => 0,
            'trial_end' => 1395341131,
            'plan_name' => 'Agency',
            'plan_amount' => self::AMOUNT,
            'plan_currency' => 'gbp'
        ];
    }

    /**
     *
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.trial_will_end';
    }

    /**
     *
     * @return string
     */
    protected function getListenerMethodName() {
        $namespaceParts = explode('\\', __NAMESPACE__);
        return $namespaceParts[count($namespaceParts) - 1];
    }

    /**
     * @return string
     */
    protected function getExpectedFormattedAmount() {
        return number_format(self::AMOUNT / 100, 2);
    }

    public function testHasMailHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    public function testHasNoMailError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testNotificationMessageContainsFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedFormattedAmount());
    }

}