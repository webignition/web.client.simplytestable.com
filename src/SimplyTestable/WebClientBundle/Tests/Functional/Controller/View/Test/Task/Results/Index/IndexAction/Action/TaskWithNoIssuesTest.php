<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Action;

class TaskWithNoIssuesTest extends CausesRedirectTest {

    const TASK_ID = 1;

    protected function getTaskId() {
        return self::TASK_ID;
    }

}