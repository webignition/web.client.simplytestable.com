<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\StatusChange;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'is_status_change' => 1,
                'previous_subscription_status' => 'trialing',
                'subscription_status' => 'active',
                'plan_name' => 'Agency',
            ]
        );
    }

}