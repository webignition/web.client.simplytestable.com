<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class RetestTest extends BaseFunctionalTest {

    public function testRetestButtonIsPresent() {
        $this->assertRetestFeatureIsPresent();
    }

}