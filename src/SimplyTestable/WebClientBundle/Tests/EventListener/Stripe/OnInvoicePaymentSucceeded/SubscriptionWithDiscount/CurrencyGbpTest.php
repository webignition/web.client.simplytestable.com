<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded\SubscriptionWithDiscount;

class CurrencyGbpTest extends ListenerTest {

    protected function getCurrency() {
        return 'gbp';
    }

    protected function getExpectedCurrencySymbol() {
        return '£';
    }
}