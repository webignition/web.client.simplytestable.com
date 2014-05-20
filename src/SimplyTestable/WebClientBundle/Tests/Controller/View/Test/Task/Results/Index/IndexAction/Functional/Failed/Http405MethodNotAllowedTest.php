<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http405MethodNotAllowedTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 405 Method Not Allowed';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}