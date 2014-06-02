<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Action\RemoteTestSummaryRetrievalError;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Action\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    public function getExpectedResponseStatusCode() {
        return null;
    }

//    const WEBSITE = 'http://example.com/';
//    const TEST_ID = 1;
//
//    protected function getHttpFixtureItems() {
//        return $this->getHttpFixtureContents($this->getFixturesDataPath());
//    }
//
//    protected function getActionMethodArguments() {
//        return array(
//            'website' => self::WEBSITE,
//            'test_id' => self::TEST_ID
//        );
//    }
//
//
//    protected function getRequestAttributes() {
//        return array(
//            'website' => self::WEBSITE,
//            'test_id' => self::TEST_ID
//        );
//    }


}