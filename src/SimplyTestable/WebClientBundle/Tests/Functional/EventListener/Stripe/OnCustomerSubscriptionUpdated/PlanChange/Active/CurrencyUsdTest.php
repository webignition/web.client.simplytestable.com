<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange\Active;

class CurrencyUsdTest extends CurrencyTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'currency' => 'usd',
            ]
        );
    }

    protected function getExpectedCurrencySymbol() {
        return '$';
    }

}