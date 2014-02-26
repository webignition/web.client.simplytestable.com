<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnInvoicePaymentFailedTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'invoice.payment_failed';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    }

    
    public function testNotificationIsIssued() {        
        $this->callListener(array(
            'lines' => array(
                array(
                    'proration' => 0,
                    'plan_name' => 'Personal',
                    'period_start' => 1379776581,
                    'period_end' => 1380368580,
                    'amount' => 900
                )                
            ),
            'total' => 900,
            'amount_due' => 900, 
            'invoice_id' => 'in_2nL671LyaO5mbg'
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());           
    }
    
}