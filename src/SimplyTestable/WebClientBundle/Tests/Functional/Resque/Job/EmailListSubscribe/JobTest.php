<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Resque\Job\EmailListSubscribe;

use SimplyTestable\WebClientBundle\Tests\Functional\Resque\Job\JobTest as BaseJobTest;

class JobTest extends BaseJobTest {

    protected function getArgs() {
        return [
            'listId' => 'announcements',
            'email' => 'user@example.com'
        ];
    }


    protected function getExpectedQueue() {
        return 'email-list-subscribe';
    }

}
