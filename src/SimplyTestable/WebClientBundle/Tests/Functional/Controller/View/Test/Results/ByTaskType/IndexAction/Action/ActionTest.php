<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\ByTaskType\IndexAction\Action;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath());
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1,
            'task_type' => 'html validation',
            'filter' => 'by-error'
        );
    }

    protected function getRequestAttributes() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1,
            'task_type' => 'html validation',
            'filter' => 'by-error'
        );
    }

    protected function getExpectedResponseStatusCode() {
        return 200;
    }
}