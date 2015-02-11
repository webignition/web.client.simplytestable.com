<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange\HasCard1;

class CurrencyGbpTest extends CurrencyTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'currency' => 'gbp',
            ]
        );
    }

    protected function getExpectedCurrencySymbol() {
        return '£';
    }

}