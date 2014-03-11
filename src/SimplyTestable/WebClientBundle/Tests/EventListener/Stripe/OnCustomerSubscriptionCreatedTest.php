<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnCustomerSubscriptionCreatedTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.created';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    } 
    
    
    public function testStatusTrialingHasCardFalse() {        
        $this->callListener(array(
            'status' => 'trialing',
            'has_card' => 0,
            'plan_name' => 'Basic',
            'trial_end' => '1392941131',
            'trial_period_days' => 30
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }
    
    public function testStatusTrialingHasCardTrue() {
        $this->callListener(array(
            'status' => 'trialing',
            'has_card' => 1,
            'plan_name' => 'Basic',
            'trial_end' => '1392941131',
            'trial_period_days' => 30            
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
    }
   
    public function testStatusActive() {
        $amountInPence = 900;
        
        $this->callListener(array(
            'status' => 'active',
            'plan_name' => 'Basic',
            'amount' => $amountInPence
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());
        $this->assertNotificationMessageContains(number_format($amountInPence / 100, 2));
    }  
    
}