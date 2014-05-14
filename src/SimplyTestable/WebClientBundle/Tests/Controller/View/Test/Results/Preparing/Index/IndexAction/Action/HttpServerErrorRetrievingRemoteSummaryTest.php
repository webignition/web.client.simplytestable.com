<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Preparing\Index\IndexAction\Action;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\ActionExceptionTest;

class HttpServerErrorRetrievingRemoteSummaryTest extends ActionExceptionTest {

    protected function getHttpFixtureItems() {
        return $this->getHttpFixtureContents($this->getFixturesDataPath($this->getName()));
    }

    protected function getActionMethodArguments() {
        return array(
            'website' => 'http://example.com/',
            'test_id' => 1
        );
    }

    protected function getExpectedExceptionClass() {
        return 'SimplyTestable\WebClientBundle\Exception\WebResourceException';
    }

    protected function getExpectedExceptionCode() {
        return 500;
    }

    protected function getExpectedExceptionMessage() {
        return 'Internal Server Error';
    }

}