<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {
    
    /**
     *
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.created';
    }


    /**
     *
     * @return string
     */
    protected function getListenerMethodName() {
        $namespaceParts = explode('\\', __NAMESPACE__);
        return $namespaceParts[count($namespaceParts) - 1];
    }
    
}