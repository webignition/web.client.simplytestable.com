<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentSucceeded;

class SubscriptionWithoutDiscountTest extends ListenerTest {

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
            'has_discount' => 0
        ];
    }

    public function testMailServiceHasHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }
    
    public function testMailServiceHasNoError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }
    
}