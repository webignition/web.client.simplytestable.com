<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

class CssValidationTest extends SucceededTest {

    const EXPECTED_HEADING = 'CSS validation report';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}