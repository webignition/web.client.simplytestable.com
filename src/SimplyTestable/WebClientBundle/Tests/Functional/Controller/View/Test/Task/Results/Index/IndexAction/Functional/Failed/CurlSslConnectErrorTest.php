<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CurlSslConnectErrorTest extends FailedTest {

    const EXPECTED_HEADING = 'SSL Error!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}