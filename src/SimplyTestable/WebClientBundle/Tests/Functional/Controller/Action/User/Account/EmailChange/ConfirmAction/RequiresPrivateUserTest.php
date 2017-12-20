<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Action\User\Account\EmailChange\ConfirmAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Base\RequiresPrivateUserTest as BaseRequiresPrivateUserTest;

class RequiresPrivateUserTest extends BaseRequiresPrivateUserTest {

    protected function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            "HTTP/1.1 200 OK",
        )));
    }

}