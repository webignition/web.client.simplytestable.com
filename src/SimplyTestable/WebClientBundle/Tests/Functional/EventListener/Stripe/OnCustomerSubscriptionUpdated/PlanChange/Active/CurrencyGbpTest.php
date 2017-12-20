<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange\Active;

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