<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Preparing\Index\IndexAction\Action;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\ActionExceptionTest;

class CurlErrorRetrievingRemoteSummaryTest extends ActionExceptionTest {

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
        return 'Guzzle\Http\Exception\CurlException';
    }

    protected function getExpectedExceptionCode() {
        return 0;
    }

    protected function getExpectedExceptionMessage() {
        return '';
    }

}