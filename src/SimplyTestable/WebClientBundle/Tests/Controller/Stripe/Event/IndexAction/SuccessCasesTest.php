<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Stripe\Event\IndexAction;

class SuccessCasesTest extends ActionTest {
    
    public function testCustomerSubscriptionCreatedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'customer.subscription.created',
                'status' => 'trialing',
                'has_card' => 0
            )
        ));
    }
    
    public function testInvoiceCreatedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'invoice.created'
            )
        ));
    }
    
    public function testInvoicePaymentSucceededEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'invoice.payment_succeeded'
            )
        ));
    }
    
    public function testCustomerSubscriptionTrialWillEndEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'customer.subscription.trial_will_end'
            )
        ));
    }
    
    public function testCustomerSubscriptionUpdatedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'customer.subscription.updated'
            )
        ));
    }    
    
    public function testInvoicePaymentFailedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'event' => 'invoice.payment_failed'
            )
        ));
    } 
}
