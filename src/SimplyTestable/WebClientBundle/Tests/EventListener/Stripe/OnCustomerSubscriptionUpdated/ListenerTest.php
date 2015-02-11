<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionUpdated;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    public function setUp() {
        parent::setUp();

        $this->callListener($this->getListenerData());
    }

    protected function getListenerData() {
        return [];
    }
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.updated';
    }

    /**
     *
     * @return string
     */
    protected function getListenerMethodName() {
        $namespaceParts = explode('\\', __NAMESPACE__);
        return $namespaceParts[count($namespaceParts) - 1];
    }

    public function testHasMailHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testHasNoMailError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }
    
}