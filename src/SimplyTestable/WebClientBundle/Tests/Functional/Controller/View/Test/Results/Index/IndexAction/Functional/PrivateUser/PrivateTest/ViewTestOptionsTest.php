<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class ViewTestOptionsTest extends BaseFunctionalTest {

    public function testTestOptionsCanBeChosen() {
        $this->assertTestOptionsChangeControlIsPresent();
    }
    
}