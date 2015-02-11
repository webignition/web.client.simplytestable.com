<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\Currency;

class CurrencyUsdTest extends CurrencyTest {

    protected function getCurrencyString() {
        return 'usd';
    }

    protected function getExpectedCurrencySymbol() {
        return '$';
    }

}