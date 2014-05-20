<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Succeeded;

class LinkIntegrityTest extends SucceededTest {

    const EXPECTED_HEADING = 'Link integrity report';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}