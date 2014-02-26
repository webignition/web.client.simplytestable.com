<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Stripe\Event\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getActionName() {
        return 'indexAction';
    }

    protected function getControllerName() {
        return self::STRIPE_EVENT_CONTROLLER_NAME;
    }
    
//    public function testEmptyPostDataReturns400() {
//        $this->performActionTest(array(
//            'statusCode' => 400
//        ));
//    }
    
//    public function testEmptyPostDataReturns400() {
//        $response =  $this->performActionTest(array(
//            'statusCode' => 4
//        ), array(
//            'postData' => array(
//                'http://example.com/',
//                1
//            )
//        ));
//    }  
    
    //public function 
}
