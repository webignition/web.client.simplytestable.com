<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Action;

class InvalidTaskTest extends CausesRedirectTest {

    const TASK_ID = 999;

    protected function getTaskId() {
        return self::TASK_ID;
    }

}