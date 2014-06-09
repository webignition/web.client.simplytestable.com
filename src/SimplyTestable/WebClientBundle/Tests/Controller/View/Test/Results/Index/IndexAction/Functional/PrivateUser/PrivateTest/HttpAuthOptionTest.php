<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class HttpAuthOptionTest extends BaseFunctionalTest {

    public function testHttpAuthOptionChangeControlIsPresent() {
        $this->assertHttpAuthOptionChangeControlIsPresent();
    }
    
}