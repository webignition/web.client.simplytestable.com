<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class NavbarContentTest extends BaseFunctionalTest {


    public function testNavbarContainsSignInButton() {
        $this->assertNavbarContainsSignInButton();
    }

    public function testNavbarSignInButtonUrl() {
        $this->assertNavbarSignInButtonUrl();
    }

    public function testNavbarContainsCreateAccountButton() {
        $this->assertNavbarContainsCreateAccountButton();
    }

    public function testNavbarCreateAccountButtonUrl() {
        $this->assertNavbarCreateAccountButtonUrl();
    }

}