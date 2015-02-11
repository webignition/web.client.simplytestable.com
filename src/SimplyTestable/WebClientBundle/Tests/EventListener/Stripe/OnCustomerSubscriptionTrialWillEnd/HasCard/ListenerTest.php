<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\HasCard;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionTrialWillEnd\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function getListenerData() {
        return array_merge(
            parent::getListenerData(),
            [
                'has_card' => $this->getHasCard()
            ]
        );
    }

    abstract protected function getHasCard();
}