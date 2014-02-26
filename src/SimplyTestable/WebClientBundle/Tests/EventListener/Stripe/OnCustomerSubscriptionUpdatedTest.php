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
    
/**
plan change:
                    'is_plan_change' => 1,
                    'old_plan' => $eventData->data->previous_attributes->plan->name,
                    'new_plan' => $eventData->data->object->plan->name,
                    'new_amount' => $eventData->data->object->plan->amount,
                    'subscription_status' => $eventData->data->object->status 
 * 
status change:
active to past_due: ?? possibly will be used when downgrading to free when
 *                  sub payment fails. awaiting to see events for cus_3YuJjKevFxGS6L
active to canceled or past_due to canceled
                            'is_status_change' => 1,
                            'previous_subscription_status' => $eventData->data->previous_attributes->status,
                            'subscription_status' => $eventData->data->object->status
 * 
trialing to active
                            'is_status_change' => 1,
                            'previous_subscription_status' => $eventData->data->previous_attributes->status,
                            'subscription_status' => $eventData->data->object->status,
                            'has_card' => (int)$this->getStripeCustomerHasCard($stripeCustomer)
 * 
trialing to canceled
                            'is_status_change' => 1,
                            'previous_subscription_status' => $eventData->data->previous_attributes->status,
                            'subscription_status' => $eventData->data->object->status,
                            'trial_days_remaining' => $this->getUserAccountPlanFromEvent($event)->getStartTrialPeriod()
 * 
 * plan change 1 status active
 * plan change 1 status trialing
 * 
 * active to past due ???:
 * active to cancelled or past_due to cancelled ??? <-- probably downgrade at this point on the core app side:
 * trialing to active:
 * transition=trialing_to_active-has_card=0
 * transition=trialing_to_active-has_card=1
 * transition=active_to_canceled 
 */    
    
    
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
    
    public function testStatusChangeActiveToCanceled() {
        $this->callListener(array(
            'is_status_change' => 1,
            'previous_subscription_status' => 'active',
            'subscription_status' => 'canceled',
            'plan_name' => 'Agency',
            'plan_amount' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }    
    
}