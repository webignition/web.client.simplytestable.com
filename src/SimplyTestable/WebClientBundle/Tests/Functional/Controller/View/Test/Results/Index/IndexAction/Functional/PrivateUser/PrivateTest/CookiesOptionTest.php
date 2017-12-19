<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class CookiesOptionTest extends BaseFunctionalTest {

    public function testCookiesAvailableChangeControlIsPresent() {
        $this->assertCookiesOptionAvailableChangeControlIsPresent();
    }

    public function testCookiesNotAvailableChangeControlIsNotPresent() {
        $this->assertCookiesOptionNotAvailableChangeControlIsNotPresent();
    }
}