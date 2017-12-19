<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http500InternalServerErrorTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 500 Internal Server Error';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}