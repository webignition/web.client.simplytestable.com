<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class Http401AuthorizationRequiredTest extends FailedTest {

    const EXPECTED_HEADING = 'HTTP 401 Authorization Required';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }
}