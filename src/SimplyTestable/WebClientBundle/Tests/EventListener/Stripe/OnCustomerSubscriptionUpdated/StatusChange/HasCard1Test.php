<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange;

class HasCard1Test extends ListenerTest {

    const AMOUNT = 1900;

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'has_card' => 1,
                'plan_amount' => self::AMOUNT
            ]
        );
    }

    private function getExpectedFormattedAmount() {
        return number_format(self::AMOUNT / 100, 2);
    }

    public function testNotificationMessageContainsFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedFormattedAmount());
    }

}