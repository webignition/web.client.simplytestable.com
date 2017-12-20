<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class RedirectLoopTest extends FailedTest {

    const EXPECTED_HEADING = 'Redirect Loop Detected';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}