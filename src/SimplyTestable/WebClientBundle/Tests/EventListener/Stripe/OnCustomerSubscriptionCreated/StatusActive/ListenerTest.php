<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

use SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    const AMOUNT = 900;
    const DEFAULT_CURRENCY = 'gbp';

    public function setUp() {
        parent::setUp();

        $this->callListener(array(
            'status' => 'active',
            'plan_name' => 'Basic',
            'amount' => self::AMOUNT,
            'currency' => $this->getCurrencyString()
        ));
    }


    /**
     * @return string
     */
    protected function getCurrencyString() {
        return self::DEFAULT_CURRENCY;
    }

    public function testHasMailHistory() {
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
    }

    public function testHasNoMailError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }


    /**
     * @return string
     */
    protected function getExpectedFormattedAmount() {
        return number_format(self::AMOUNT / 100, 2);
    }

}