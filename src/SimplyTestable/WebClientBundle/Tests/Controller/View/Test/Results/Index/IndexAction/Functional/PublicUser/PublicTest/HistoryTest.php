<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class HistoryTest extends BaseFunctionalTest {

    public function testHistoryLinkIsPresent() {
        $this->assertHistoryLinkIsPresent();
    }
    
}