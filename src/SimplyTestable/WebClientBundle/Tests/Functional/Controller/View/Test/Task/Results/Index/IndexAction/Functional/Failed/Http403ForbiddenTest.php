<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http403ForbiddenTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 403 Forbidden';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}