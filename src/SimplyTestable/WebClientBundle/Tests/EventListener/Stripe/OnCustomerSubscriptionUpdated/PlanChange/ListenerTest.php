<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\PlanChange;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    const AMOUNT = 1900;

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'is_plan_change' => '1',
                'new_amount' => self::AMOUNT,
                'old_plan' => 'Personal',
                'new_plan' => 'Agency',
                'trial_end' => 1392941131
            ]
        );
    }
    
}