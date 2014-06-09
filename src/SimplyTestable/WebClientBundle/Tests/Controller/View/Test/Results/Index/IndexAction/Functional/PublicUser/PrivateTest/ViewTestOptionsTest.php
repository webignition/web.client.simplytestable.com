<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser\FunctionalTest as BaseFunctionalTest;

class ViewTestOptionsTest extends BaseFunctionalTest {

    public function testTestOptionsCanBeChosen() {
        $this->assertTestOptionsChangeControlIsNotPresent();
    }
    
}