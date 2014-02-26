<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\Stripe;

class OnInvoiceCreatedTest extends ListenerTest {
    
    /**
     * 
     * @return string
     */
    protected function getEventName() {
        return 'invoice.created';
    }     
    
    /**
     * 
     * @return string
     */
    protected function getListenerMethodName() {
        return str_replace('Test', '', substr(__CLASS__, strrpos(__CLASS__, '\\') + 1));
    } 
    
    /**
     * test with single line
     * test with multiple lines
     * test with proration set
     */
    
    public function testWithSingleLine() {        
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
            'next_payment_attempt' => 1382368680,
            'invoice_id' => 'in_2c6Kz0tw4CBlOL',
            'total' => 1900,
            'amount_due' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }    
    
    
    public function testWithMultipleLines() {        
        $this->callListener(array(
            'lines' => array(
                array(
                    'proration' => 1,
                    'plan_name' => 'Agency',
                    'period_start' => 1379776581,
                    'period_end' => 1380368580,              
                    'amount' => 1000
                ),
                array(
                    'proration' => 1,
                    'plan_name' => 'Agency',
                    'period_start' => 1380368580,
                    'period_end' => 1382368580,
                    'amount' => 900
                )                   
            ),
            'next_payment_attempt' => 1382368680,
            'invoice_id' => 'in_2c6Kz0tw4CBlOL',
            'total' => 1900,
            'amount_due' => 1900
        ));
        
        $this->assertEquals(1, $this->getMailService()->getSender()->getHistory()->count());
        $this->assertFalse($this->getMailService()->getSender()->getLastResponse()->isError());        
    }
    
}