<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange\Active;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest {

    // add currency tests

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'subscription_status' => 'active',
            ]
        );
    }

    protected function getExpectedFormattedAmount() {
        return number_format(self::AMOUNT / 100, 2);
    }

    public function testNotificationMessageContainsFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedFormattedAmount());
    }
    
}