<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\User\Account\Card\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\RequiresPrivateUserTest as BaseRequiresPrivateUserTest;

class RequiresPrivateUserTest extends BaseRequiresPrivateUserTest {
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            "HTTP/1.1 200 OK",
        )));
    }
    
}