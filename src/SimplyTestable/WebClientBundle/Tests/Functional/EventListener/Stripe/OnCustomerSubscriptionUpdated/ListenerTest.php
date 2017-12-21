<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionUpdated;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function setUp() {
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
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    public function testHasNoMailError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

}