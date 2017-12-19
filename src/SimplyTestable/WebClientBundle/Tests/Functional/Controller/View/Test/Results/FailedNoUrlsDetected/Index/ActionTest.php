<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\FailedNoUrlsDetected\Index\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionTest as BaseActionTest;

class ActionTest extends BaseActionTest {

    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath($this->getName()));
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1
        );
    }

    protected function getExpectedResponseStatusCode() {
        return 200;
    }
}