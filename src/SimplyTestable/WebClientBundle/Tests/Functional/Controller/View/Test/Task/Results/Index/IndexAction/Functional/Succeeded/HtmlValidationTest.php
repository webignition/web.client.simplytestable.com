<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

class HtmlValidationTest extends SucceededTest {

    const EXPECTED_HEADING = 'HTML validation report';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}