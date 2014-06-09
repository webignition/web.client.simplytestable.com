<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class HttpAuthOptionTest extends BaseFunctionalTest {

    public function testHttpAuthOptionChangeControlIsPresent() {
        $this->assertHttpAuthOptionChangeControlIsPresent();
    }
    
}