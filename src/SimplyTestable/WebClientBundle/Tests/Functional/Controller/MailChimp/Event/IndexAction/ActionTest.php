<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\MailChimp\Event\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {
    
    protected function getActionName() {
        return 'indexAction';
    }

    protected function getControllerName() {
        return self::MAILCHIMP_EVENT_CONTROLLER_NAME;
    }
}
