<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnInvoicePaymentSucceeded;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    abstract protected function getStripeEventData();

    /**
     *
     * @return string
     */
    protected function getEventName() {
        return 'invoice.payment_succeeded';
    }

    /**
     *
     * @return string
     */
    protected function getListenerMethodName() {
        $namespaceParts = explode('\\', __NAMESPACE__);
        return $namespaceParts[count($namespaceParts) - 1];
    }


    protected function setUp() {
        parent::setUp();

        $this->callListener($this->getStripeEventData());
    }


    public function testMailServiceHasHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

}