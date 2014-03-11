<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnCustomerSubscriptionTrialWillEndTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'customer.subscription.trial_will_end';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    } 
    
    
    public function testHasCardTrue() {        
        $amountInPence = 1900;
        
        $this->callListener(array(
            'has_card' => 1,
            'trial_end' => 1395341131,
            'plan_name' => 'Agency',
            'plan_amount' => $amountInPence            
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
        $this->assertNotificationMessageContains(number_format($amountInPence / 100, 2));
    }
    
    public function testHasCardFalse() {        
        $amountInPence = 1900;
        
        $this->callListener(array(
            'has_card' => 0,
            'trial_end' => 1395341131,
            'plan_name' => 'Agency',
            'plan_amount' => $amountInPence
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
        $this->assertNotificationMessageContains(number_format($amountInPence / 100, 2));
    }    
    
}