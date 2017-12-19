<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http404NotFoundTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 404 Not Found';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}