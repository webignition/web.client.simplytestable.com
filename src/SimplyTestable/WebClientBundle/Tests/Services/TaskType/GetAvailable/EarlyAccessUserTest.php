<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TaskType\GetAvailable;

use SimplyTestable\WebClientBundle\Model\User;

class EarlyAccessUserTest extends GetAvailableTest {

    protected function getUser() {
        $user = new User();
        $user->setUsername('jon@webignition.net');
        $user->setPassword('foobar');

        return $user;
    }

    protected function getExpectedTaskTypeKeys() {
        return [
            'html-validation',
            'css-validation',
            'js-static-analysis',
            'link-integrity',
            'foo-bar'
        ];
    }


}
