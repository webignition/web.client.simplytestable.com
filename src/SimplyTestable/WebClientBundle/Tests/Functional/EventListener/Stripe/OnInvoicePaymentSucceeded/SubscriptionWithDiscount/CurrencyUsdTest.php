<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnInvoicePaymentSucceeded\SubscriptionWithDiscount;

class CurrencyUsdTest extends ListenerTest {

    protected function getCurrency() {
        return 'usd';
    }

    protected function getExpectedCurrencySymbol() {
        return '$';
    }
}