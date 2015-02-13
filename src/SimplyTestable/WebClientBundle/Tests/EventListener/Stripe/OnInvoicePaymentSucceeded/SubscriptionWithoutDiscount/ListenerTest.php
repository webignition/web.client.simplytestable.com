<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded\SubscriptionWithoutDiscount;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function getStripeEventData() {
        return [
            'lines' => [
                [
                    'proration' => 0,
                    'plan_name' => 'Personal',
                    'period_start' => 1379776581,
                    'period_end' => 1382368580,
                    'amount' => 1900
                ]
            ],
            'invoice_id' => 'in_2c6Kz0tw4CBlOL',
            'subtotal' => 1900,
            'total' => 1900,
            'amount_due' => 1900,
            'has_discount' => 0,
            'currency' => $this->getCurrency()
        ];
    }


    abstract protected function getCurrency();
    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsLineAmount() {
        $this->assertNotificationMessageContains('(' . $this->getExpectedCurrencySymbol() . '19.00)');
    }
    
}