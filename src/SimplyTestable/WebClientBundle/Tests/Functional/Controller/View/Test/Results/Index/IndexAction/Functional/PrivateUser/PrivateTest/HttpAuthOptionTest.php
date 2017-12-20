<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class HttpAuthOptionTest extends BaseFunctionalTest {

    public function testHttpAuthAvailableChangeControlIsPresent() {
        $this->assertHttpAuthOptionAvailableChangeControlIsPresent();
    }

    public function testHttpAuthNotAvailableChangeControlIsNotPresent() {
        $this->assertHttpAuthOptionNotAvailableChangeControlIsNotPresent();
    }
    
}