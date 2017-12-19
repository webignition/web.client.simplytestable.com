<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http502BadGatewayTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 502 Bad Gateway';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}