<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange\HasCard1;

abstract class CurrencyTest extends ListenerTest {

    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsCurrencySymbolAndFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedCurrencySymbol() . $this->getExpectedFormattedAmount());
    }

}