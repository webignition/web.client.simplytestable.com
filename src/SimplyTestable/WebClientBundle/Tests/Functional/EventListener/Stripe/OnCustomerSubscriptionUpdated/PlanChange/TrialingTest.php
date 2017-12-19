<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange;

class TrialingTest extends ListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'subscription_status' => 'trialing',
            ]
        );
    }

}