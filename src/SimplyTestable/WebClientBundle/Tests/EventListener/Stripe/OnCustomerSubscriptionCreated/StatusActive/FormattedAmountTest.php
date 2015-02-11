<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe\OnCustomerSubscriptionCreated\StatusActive;

class FormattedAmountTest extends ListenerTest {

    public function testNotificationMessageContainsFormattedAmount() {
        $this->assertNotificationMessageContains($this->getExpectedFormattedAmount());
    }

}