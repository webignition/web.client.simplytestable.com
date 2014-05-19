<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\FunctionalTest;

class LinkIntegrityTest extends FunctionalTest {

    const TASK_ID = 1;

    protected function getTaskId() {
        return self::TASK_ID;
    }

    public function testTitleContainsTruncatedUrl() {
        $this->assertTitleContainsText(
            'Results for ' . substr(str_replace('http://', '', $this->getWebsite()), 0, 64 ) . '…'
        );
    }

    public function testHeading() {
        $this->assertHeadingContains('Link integrity report');
    }

}