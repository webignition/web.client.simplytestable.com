<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\PrivateTest;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser\FunctionalTest as BaseFunctionalTest;

class LockUnlockTest extends BaseFunctionalTest {

    public function testLockUnlockFeatureIsPresent() {
        $this->assertLockUnlockFeatureIsPresent();
    }
}