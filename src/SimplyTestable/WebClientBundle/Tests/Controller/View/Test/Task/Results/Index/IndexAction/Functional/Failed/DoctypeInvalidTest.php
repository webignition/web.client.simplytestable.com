<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class DoctypeInvalidTest extends FailedTest {

    const EXPECTED_HEADING = 'Invalid Document Type!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}