<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class CookiesOptionTest extends BaseFunctionalTest {

    public function testCookiesAvailableChangeControlIsNotPresent() {
        $this->assertCookiesOptionAvailableChangeControlIsNotPresent();
    }

    public function testCookiesNotAvailableChangeControlIsPresent() {
        $this->assertCookiesOptionNotAvailableChangeControlIsPresent();
    }
    
}