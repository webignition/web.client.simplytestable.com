<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnCustomerSubscriptionDeletedTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.deleted';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }

    
    
    public function testUserCancelledDuringTrialSingleTrialDayRemaining() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'during_trial' => 1,
            'trial_days_remaining' => 1
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
        $this->assertNotificationMessageContains('1 day');
    }
    
    public function testUserCancelledDuringTrialMultipleTrialDaysRemaining() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'during_trial' => 1,
            'trial_days_remaining' => 20
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
        $this->assertNotificationMessageContains('20 days');
    }    
    
    
    public function testUserCancelledAfterTrial() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'user',
            'during_trial' => 0
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());         
    }
    
    
    public function testSystemCancelledFollowingPaymentFailure() {
        $this->callListener(array(
            'plan_name' => 'Agency',
            'actioned_by' => 'system'
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());         
    }

    
}