<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\Currency;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\ListenerTest;

abstract class CurrencyTest extends ListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'plan_currency' => $this->getCurrencyString()
            ]
        );
    }

    abstract protected function getCurrencyString();
    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsCurrencySymbolAndFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedCurrencySymbol() . $this->getExpectedFormattedAmount());
    }

}