<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange;

class ActiveTest extends ListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'subscription_status' => 'active',
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