<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\MailChimp\Event\IndexAction;

class SuccessCasesTest extends ActionTest {
    
    public function testSubscribeEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'subscribe',
                'data' => array(
                    'list_id' => '1224633c43',
                    'email' => 'user@example.com'
                )
            )
        ));                
    }   
    

    public function testUnsubscribeEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'unsubscribe',
                'data' => array(
                    'list_id' => '1224633c43',
                    'email' => 'user@example.com'
                )
            )
        ));                
    } 
    
    
    public function testUpEmailEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'upemail',
                'data' => array(
                    'list_id' => '1224633c43',
                    'new_email' => 'new-user@example.com',
                    'old_email' => 'old-user@example.com'
                )
            )
        ));                
    } 
    
    
    public function testCleanedEventReturns200() {
        $this->performActionTest(array(
            'statusCode' => 200
        ), array(
            'postData' => array(
                'type' => 'cleaned',
                'data' => array(
                    'list_id' => '1224633c43',
                    'email' => 'user@example.com'
                )
            )
        ));                
    }     
}
