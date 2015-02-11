<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded\SubscriptionWithoutDiscount;

class CurrencyUsdTest extends ListenerTest {

    protected function getCurrency() {
        return 'usd';
    }

    protected function getExpectedCurrencySymbol() {
        return '$';
    }
}