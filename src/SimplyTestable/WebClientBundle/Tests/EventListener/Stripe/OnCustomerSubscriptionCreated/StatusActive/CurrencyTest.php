<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

abstract class CurrencyTest extends ListenerTest {

    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsCurrencySymbolAndFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedCurrencySymbol() . $this->getExpectedFormattedAmount());
    }

}