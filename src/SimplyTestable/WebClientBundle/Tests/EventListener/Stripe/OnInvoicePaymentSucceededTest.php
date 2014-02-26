<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnInvoicePaymentSucceededTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'invoice.payment_succeeded';
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
                    'period_end' => 1382368580,
                    'amount' => 1900
                )               
            ),
            'invoice_id' => 'in_2c6Kz0tw4CBlOL',
            'total' => 1900,
            'amount_due' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }
    
}