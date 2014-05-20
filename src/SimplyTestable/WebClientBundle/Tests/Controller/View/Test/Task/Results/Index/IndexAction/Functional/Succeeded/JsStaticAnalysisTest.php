<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

class JsStaticAnalysisTest extends SucceededTest {
    const EXPECTED_HEADING = 'JS static analysis report';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}