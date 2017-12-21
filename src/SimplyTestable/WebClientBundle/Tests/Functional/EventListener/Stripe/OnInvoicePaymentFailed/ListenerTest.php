<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnInvoicePaymentFailed;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\ListenerTest as BaseListenerTest;

class ListenerTest extends BaseListenerTest {

    const LINE_AMOUNT = 900;
    const TOTAL = 900;
    const AMOUNT_DUE = 900;

    protected function setUp() {
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
                    'amount' => self::LINE_AMOUNT
                ]
            ],
            'total' => self::TOTAL,
            'amount_due' => self::AMOUNT_DUE,
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

    protected function getExpectedFormattedLineAmount() {
        return number_format(self::LINE_AMOUNT / 100, 2);
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