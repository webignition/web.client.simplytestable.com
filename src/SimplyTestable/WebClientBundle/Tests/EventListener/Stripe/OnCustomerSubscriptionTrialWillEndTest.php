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
        $this->callListener(array(
            'has_card' => 1,
            'trial_end' => 1395341131,
            'plan_name' => 'Agency',
            'plan_amount' => 1900            
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }
    
    public function testHasCardFalse() {        
        $this->callListener(array(
            'has_card' => 0,
            'trial_end' => 1395341131,
            'plan_name' => 'Agency',
            'plan_amount' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }    
    
}