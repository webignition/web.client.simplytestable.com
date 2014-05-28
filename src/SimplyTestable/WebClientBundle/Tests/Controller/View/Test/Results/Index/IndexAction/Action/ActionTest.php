<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Action;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;

    /**
     *
     * remote test summary
     */


    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath());
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID
        );
    }


    protected function getRequestAttributes() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID
        );
    }


}