<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnInvoicePaymentFailed;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest {

    public function setUp() {
        parent::setUp();

        $this->callListener($this->getListenerData());
    }

    protected function getListenerData() {
        return [
            'lines' => [
                [
                    'proration' => 0,
                    'plan_name' => 'Personal',
                    'period_start' => 1379776581,
                    'period_end' => 1380368580,
                    'amount' => 900
                ]
            ],
            'total' => 900,
            'amount_due' => 900,
            'invoice_id' => 'in_2nL671LyaO5mbg',
            'currency' => 'gbp'
        ];
    }
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'invoice.payment_failed';
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