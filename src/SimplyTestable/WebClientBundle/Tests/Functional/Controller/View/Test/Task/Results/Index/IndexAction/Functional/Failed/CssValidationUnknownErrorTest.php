<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CssValidationUnknownErrorTest extends FailedTest {

    const EXPECTED_HEADING = 'Unknown Validator Error!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}