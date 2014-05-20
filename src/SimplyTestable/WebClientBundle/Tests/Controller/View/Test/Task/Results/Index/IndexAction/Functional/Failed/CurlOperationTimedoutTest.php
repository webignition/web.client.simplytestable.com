<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CurlOperationTimedoutTest extends FailedTest {

    const EXPECTED_HEADING = 'Connection Timeout!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}