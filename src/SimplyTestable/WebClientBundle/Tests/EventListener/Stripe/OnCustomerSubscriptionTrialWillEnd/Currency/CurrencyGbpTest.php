<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\Currency;

class CurrencyGbpTest extends CurrencyTest {

    protected function getCurrencyString() {
        return 'gbp';
    }

    protected function getExpectedCurrencySymbol() {
        return '£';
    }
    
}