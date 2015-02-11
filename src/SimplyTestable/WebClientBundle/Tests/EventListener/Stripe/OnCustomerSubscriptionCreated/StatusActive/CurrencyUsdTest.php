<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

class CurrencyUsdTest extends CurrencyTest {

    protected function getCurrencyString() {
        return 'usd';
    }

    protected function getExpectedCurrencySymbol() {
        return '$';
    }

}