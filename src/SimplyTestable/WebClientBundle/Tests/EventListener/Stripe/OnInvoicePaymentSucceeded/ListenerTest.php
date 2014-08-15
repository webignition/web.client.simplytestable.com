<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\ListenerTest as BaseListenerTest;

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


    public function setUp() {
        parent::setUp();

        $this->callListener($this->getStripeEventData());
    }

}