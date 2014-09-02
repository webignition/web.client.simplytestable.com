<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Action\SignUp\User\Confirm\ResendAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    abstract protected function getExpectedFlashValues();

    public function getExpectedResponseStatusCode() {
        return 302;
    }

    protected function getExpectedLocationHeaderValue() {
        return '/signup/confirm/user@example.com/';
    }

    protected function getActionMethodArguments() {
        return [
            'email' => 'user@example.com'
        ];
    }

    public function testRedirectPath() {
        $this->assertEquals($this->getExpectedLocationHeaderValue(), $this->response->headers->get('location'));
    }

    public function testFlashValues() {
        $this->assertEquals($this->getExpectedFlashValues(), $this->container->get('session')->getFlashBag()->all());
    }


//    public function testPostmark405OnSendingConfirmationEmail() {
//        $this->getMailService()->getSender()->setJsonResponse('{"ErrorCode":405,"Message":""}');
//
//        $this->performActionTest(array(
//            'statusCode' => 302,
//            'redirectPath' => '/signup/',
//            'flash' => array(
//                'user_create_error' => 'postmark-not-allowed-to-send'
//            )
//        ), array(
//            'postData' => array(
//                'email' => 'user@example.com',
//                'password' => 'password'
//            )
//        ));
//
//    }
//
//
//    public function testPostmark406OnSendingConfirmationEmail() {
//        $this->getMailService()->getSender()->setJsonResponse('{"ErrorCode":406,"Message":"You tried to send to a recipient that has been marked as inactive.\nFound inactive addresses: user@example.com.\nInactive recipients are ones that have generated a hard bounce or a spam complaint. "}');
//
//        $this->performActionTest(array(
//            'statusCode' => 302,
//            'redirectPath' => '/signup/',
//            'flash' => array(
//                'user_create_error' => 'postmark-inactive-recipient'
//            )
//        ), array(
//            'postData' => array(
//                'email' => 'user@example.com',
//                'password' => 'password'
//            )
//        ));
//
//    }

    

}


 