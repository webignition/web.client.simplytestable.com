<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CurlCouldntResolveHostTest extends FailedTest {

    const EXPECTED_HEADING = 'DNS Timeout!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}