<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {

    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';
    const TEST_ID = 1;
    const TASK_ID = 1;

    protected function preCall() {
        $this->getUserService()->setUser($this->makeUser());
        $this->getTestService()->get(self::WEBSITE, self::TEST_ID);
    }

    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath());
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
            'task_id' => self::TASK_ID
        );
    }

    protected function getExpectedResponseStatusCode() {
        return 200;
    }
}