<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CurlUrlMalformatTest extends FailedTest {

    const EXPECTED_HEADING = 'Bad URL!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}