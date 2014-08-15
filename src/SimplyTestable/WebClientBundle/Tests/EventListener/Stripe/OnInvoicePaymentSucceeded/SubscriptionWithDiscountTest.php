<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded;

class SubscriptionWithDiscountTest extends ListenerTest {

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
            'has_discount' => 1
        ];
    }

    public function testMailServiceHasHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }
    
    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

    public function testNotificationMessageContainsDiscountLine() {
        $this->assertNotificationMessageContains('20% off with coupon TMS (-£1.80)');
    }

    public function testNotificationMessageContainsTotalLine() {
        $this->assertNotificationMessageContains('Total: £7.20');
    }
}