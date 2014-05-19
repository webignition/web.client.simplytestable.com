<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Action;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
        $this->getTestService()->get(self::WEBSITE, self::TEST_ID);
    }

    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath());
    }

    abstract protected function getTaskId();

    protected function getActionMethodArguments() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => $this->getTaskId()
        );
    }

}