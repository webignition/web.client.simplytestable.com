<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class HistoryTest extends BaseFunctionalTest {

    public function testHistoryLinkIsPresent() {
        $this->assertHistoryLinkIsPresent();
    }
    
}