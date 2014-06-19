<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\Partial\Notification;

use SimplyTestable\WebClientBundle\Controller\View\Test\CacheableViewController;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use SimplyTestable\WebClientBundle\Interfaces\Controller\Test\RequiresValidOwner;
use Symfony\Component\HttpFoundation\Response;

class UrlLimitController extends CacheableViewController implements RequiresValidUser, RequiresValidOwner {

    protected function modifyViewName($viewName) {
        return str_replace(array(
            ':Test'
        ), array(
            ':bs3/Test'
        ), $viewName);
    }

    public function indexAction($website, $test_id) {
        return $this->renderCacheableResponse([
            'remote_test' => $this->getRemoteTest(),
            'is_public_user_test' => $this->getTest()->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
        ]);
    }

    public function getCacheValidatorParameters() {
        $test = $this->getTest();

        return array(
            'test_id' => $this->getRequest()->attributes->get('test_id'),
            'is_public_user_test' => $test->getUser() == $this->getUserService()->getPublicUser()->getUsername(),
        );
    }

    public function getInvalidOwnerResponse() {
        return new Response('');
    }

}
