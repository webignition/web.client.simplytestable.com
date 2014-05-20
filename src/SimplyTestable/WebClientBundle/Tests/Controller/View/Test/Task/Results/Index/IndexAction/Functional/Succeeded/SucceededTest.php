<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\FunctionalTest;

abstract class SucceededTest extends FunctionalTest {

    public function testTitleContainsTruncatedUrl() {
        $this->assertTitleContainsText(
            'Results for ' . substr(str_replace('http://', '', $this->getWebsite()), 0, 64 ) . '…'
        );
    }

    public function testHeading() {
        $this->assertHeadingContains($this->getExpectedHeading());
    }

    abstract protected function getExpectedHeading();

}