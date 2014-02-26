<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnCustomerSubscriptionUpdatedTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.updated';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }  
    
    
    public function testPlanChangeTrialing() {        
        $this->callListener(array(
            'is_plan_change' => '1',
            'old_plan' => 'Personal',
            'new_plan' => 'Agency',
            'new_amount' => 1900,
            'subscription_status' => 'trialing',
            'trial_end' => 1392941131
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }
    
    public function testPlanChangeActive() {
        $this->callListener(array(
            'is_plan_change' => '1',
            'old_plan' => 'Personal',
            'new_plan' => 'Agency',
            'new_amount' => 1900,
            'subscription_status' => 'active'
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    } 
    
    public function testStatusChangeTrialingToActiveHasCardFalse() {
        $this->callListener(array(
            'is_status_change' => 1,
            'previous_subscription_status' => 'trialing',
            'subscription_status' => 'active',
            'has_card' => 0,
            'plan_name' => 'Agency'
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }
    
    public function testStatusChangeTrialingToActiveHasCardTrue() {
        $this->callListener(array(
            'is_status_change' => 1,
            'previous_subscription_status' => 'trialing',
            'subscription_status' => 'active',
            'has_card' => 1,
            'plan_name' => 'Agency',
            'plan_amount' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }   
    
}