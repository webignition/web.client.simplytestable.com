<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\MailChimp\Event\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getActionName() {
        return 'indexAction';
    }

    protected function getControllerName() {
        return self::MAILCHIMP_EVENT_CONTROLLER_NAME;
    }
}
