<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class DoctypeMissingTest extends FailedTest {

    const EXPECTED_HEADING = 'Missing Document Type!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}