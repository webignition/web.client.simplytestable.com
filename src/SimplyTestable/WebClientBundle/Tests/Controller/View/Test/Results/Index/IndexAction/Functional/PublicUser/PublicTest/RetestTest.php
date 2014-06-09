<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class RetestTest extends BaseFunctionalTest {

    public function testRetestButtonIsPresent() {
        $this->assertRetestFeatureIsPresent();
    }

}