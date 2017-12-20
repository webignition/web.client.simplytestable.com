<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnInvoicePaymentSucceeded\SubscriptionWithDiscount;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnInvoicePaymentSucceeded\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function getStripeEventData() {
        return [
            'lines' => [
                [
                    'proration' => 0,
                    'plan_name' => 'Personal',
                    'period_start' => 1408031226,
                    'period_end' => 1410709626,
                    'amount' => 900
                ]
            ],
            'invoice_id' => 'in_4abfD1nt0ael6N',
            'subtotal' => '900',
            'total' => '720',
            'amount_due' => '720',
            'discount' => [
                'coupon' => 'TMS',
                'percent_off' => '20',
                'discount' => 180
            ],
            'has_discount' => 1,
            'currency' => $this->getCurrency()
        ];
    }

    abstract protected function getCurrency();
    abstract protected function getExpectedCurrencySymbol();

    public function testNotificationMessageContainsDiscountLine() {
        $this->assertNotificationMessageContains('20% off with coupon TMS (-' . $this->getExpectedCurrencySymbol() . '1.80)');
    }

    public function testNotificationMessageContainsTotalLine() {
        $this->assertNotificationMessageContains('Total: ' . $this->getExpectedCurrencySymbol() . '7.20');
    }
}