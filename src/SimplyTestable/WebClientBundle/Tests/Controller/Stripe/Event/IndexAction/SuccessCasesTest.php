<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Stripe\Event\IndexAction;

class SuccessCasesTest extends ActionTest {
    
    public function testCustomerSubscriptionCreatedStatusTrialingHasCardFalseEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.created',
                'status' => 'trialing',
                'has_card' => 0,
                'status' => 'trialing',
                'plan_name' => 'Basic',
                'trial_end' => '1392941131',
                'trial_period_days' => 30
            )
        ));                
    }
    
    public function testCustomerSubscriptionCreatedStatusTrialingHasCardTrueEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.created',
                'status' => 'trialing',
                'has_card' => 1,
                'plan_name' => 'Basic',
                'trial_end' => '1392941131',
                'trial_period_days' => 30
            )
        ));        
    } 
    
    public function testCustomerSubscriptionCreatedStatusActiveEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.created',
                'status' => 'active',
                'plan_name' => 'Basic',
                'amount' => '900'
            )
        ));        
    }
    
    
    public function testCustomerSubscriptionTrialWillEndHasCardTrueEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.trial_will_end',
                'has_card' => 1,
                'trial_end' => 1395341131,
                'plan_name' => 'Agency',
                'plan_amount' => 1900                  
            )
        ));         
    }
    
    public function testCustomerSubscriptionTrialWillEndHasCardFalseEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.trial_will_end',
                'has_card' => 0,
                'trial_end' => 1395341131,
                'plan_name' => 'Agency',
                'plan_amount' => 1900                  
            )
        ));         
    } 
    
    
    public function testCustomerSubscriptionUpdatedPlanChangeTrialingEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.updated',
                'is_plan_change' => '1',
                'old_plan' => 'Personal',
                'new_plan' => 'Agency',
                'new_amount' => 1900,
                'subscription_status' => 'trialing',
                'trial_end' => 1392941131             
            )
        ));         
    } 
    

    public function testCustomerSubscriptionUpdatedPlanChangeActiveEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.updated',
                'is_plan_change' => '1',
                'old_plan' => 'Personal',
                'new_plan' => 'Agency',
                'new_amount' => 1900,
                'subscription_status' => 'active'           
            )
        ));         
    }   
    
    
    public function testCustomerSubscriptionUpdatedStatusChangeTrialingToActiveHasCardFalseEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.updated',
                'is_status_change' => 1,
                'previous_subscription_status' => 'trialing',
                'subscription_status' => 'active',
                'has_card' => 0,
                'plan_name' => 'Agency'        
            )
        ));         
    } 
    
    
    public function testCustomerSubscriptionUpdatedStatusChangeTrialingToActiveHasCardTrueEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'customer.subscription.updated',
                'is_status_change' => 1,
                'previous_subscription_status' => 'trialing',
                'subscription_status' => 'active',
                'has_card' => 1,
                'plan_name' => 'Agency'        
            )
        ));         
    }    
    
    
    public function testInvoicePaymentFailedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'invoice.payment_failed',
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
                )
        ));         
    }       
    
    
    public function testInvoicePaymentSucceededEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'user' => 'user@example.com',
                'event' => 'invoice.payment_succeeded',
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
                )
        ));         
    }
}
