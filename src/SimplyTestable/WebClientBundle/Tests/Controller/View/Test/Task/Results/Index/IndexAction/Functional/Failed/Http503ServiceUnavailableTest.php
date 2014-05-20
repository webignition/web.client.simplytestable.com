<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http503ServiceUnavailableTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 503 Service Unavailable';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}