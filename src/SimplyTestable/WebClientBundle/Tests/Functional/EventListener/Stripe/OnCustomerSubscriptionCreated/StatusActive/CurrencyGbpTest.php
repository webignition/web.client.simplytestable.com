<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

class CurrencyGbpTest extends CurrencyTest {

    protected function getCurrencyString() {
        return 'gbp';
    }

    protected function getExpectedCurrencySymbol() {
        return '£';
    }
    
}