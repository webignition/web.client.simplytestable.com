<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange\HasCard1;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest {

    // add currency tests

    const AMOUNT = 1900;

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'has_card' => 1,
                'plan_amount' => self::AMOUNT,
                'currency' => 'gbp'
            ]
        );
    }

    protected  function getExpectedFormattedAmount() {
        return number_format(self::AMOUNT / 100, 2);
    }

    public function testNotificationMessageContainsFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedFormattedAmount());
    }

}