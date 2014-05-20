<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class RedirectLimitTest extends FailedTest {

    const EXPECTED_HEADING = 'Redirect Limit Reached';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}