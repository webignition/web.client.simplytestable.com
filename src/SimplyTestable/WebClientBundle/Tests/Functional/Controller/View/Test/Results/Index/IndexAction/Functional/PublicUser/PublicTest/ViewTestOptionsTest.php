<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PublicTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class ViewTestOptionsTest extends BaseFunctionalTest {

    public function testOptionsCanBeChosen() {
        $this->assertTestOptionsChangeControlIsPresent();
    }
    
}