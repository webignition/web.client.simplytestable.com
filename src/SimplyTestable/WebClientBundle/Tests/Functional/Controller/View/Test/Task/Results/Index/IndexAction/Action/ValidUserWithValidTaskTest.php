<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Action;

class ValidUserWithValidTaskTest extends ActionTest {

    const TASK_ID = 1;

//    protected function getHttpFixtureItems() {
//        return $this->getHttpFixtureContents($this->getFixturesDataPath());
//    }

    protected function getTaskId() {
        return self::TASK_ID;
    }

    protected function getExpectedResponseStatusCode() {
        return 200;
    }
}