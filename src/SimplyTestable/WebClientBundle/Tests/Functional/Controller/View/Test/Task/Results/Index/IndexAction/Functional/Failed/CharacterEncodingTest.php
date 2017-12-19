<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Task\Results\Index\IndexAction\Functional\Failed;

class CharacterEncodingTest extends FailedTest {

    const EXPECTED_HEADING = 'Character Encoding Confusion!';

    public function getExpectedHeading() {
        return self::EXPECTED_HEADING;
    }

}