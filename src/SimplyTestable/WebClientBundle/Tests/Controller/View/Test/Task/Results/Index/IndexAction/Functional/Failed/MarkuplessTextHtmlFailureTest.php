<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class MarkuplessTextHtmlFailureTest extends FailedTest {

    const EXPECTED_HEADING = 'No Markup Found!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}