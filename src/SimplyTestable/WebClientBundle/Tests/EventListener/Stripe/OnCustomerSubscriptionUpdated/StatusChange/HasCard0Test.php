<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange;

class HasCard0Test extends ListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'has_card' => 0,
            ]
        );
    }

}