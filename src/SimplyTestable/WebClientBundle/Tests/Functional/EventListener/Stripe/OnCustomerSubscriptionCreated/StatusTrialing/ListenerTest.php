<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusTrialing;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\Stripe\OnCustomerSubscriptionCreated\ListenerTest as BaseListenerTest;

abstract class ListenerTest extends BaseListenerTest {

    protected function setUp() {
        parent::setUp();

        $this->callListener(array(
            'status' => 'trialing',
            'has_card' => $this->getHasCard(),
            'plan_name' => 'Basic',
            'trial_end' => '1392941131',
            'trial_period_days' => 30
        ));
    }

    abstract protected function getHasCard();

    public function testHasMailHistory() {
        $postmarkSender = $this->container->get('simplytestable.services.postmark.sender');
        $this->assertNotNull($postmarkSender->getLastMessage());
        $this->assertNotNull($postmarkSender->getLastResponse());
    }

    public function testHasNoMailError() {
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }

}