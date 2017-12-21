<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionCreated\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    const AMOUNT = 900;
    const DEFAULT_CURRENCY = 'gbp';

    protected function setUp() {
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
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
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