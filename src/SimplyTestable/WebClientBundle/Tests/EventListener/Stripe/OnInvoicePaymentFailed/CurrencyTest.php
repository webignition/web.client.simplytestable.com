<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentFailed;

abstract class CurrencyTest extends ListenerTest {

    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsCurrencySymbolAndFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedCurrencySymbol() . $this->getExpectedFormattedLineAmount());
    }

}