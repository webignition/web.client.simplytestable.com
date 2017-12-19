<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class HttpAuthOptionTest extends BaseFunctionalTest {

    public function testHttpAuthAvailableChangeControlIsNotPresent() {
        $this->assertHttpAuthOptionAvailableChangeControlIsNotPresent();
    }

    public function testHttpAuthNotAvailableChangeControlIsNotPresent() {
        $this->assertHttpAuthOptionNotAvailableChangeControlIsNotPresent();
    }
    
}