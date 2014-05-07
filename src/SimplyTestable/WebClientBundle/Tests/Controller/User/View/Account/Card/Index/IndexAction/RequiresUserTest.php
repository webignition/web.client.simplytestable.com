<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\User\View\Account\Card\IndexAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\RequiresUserTest as BaseRequiresUserTest;

class RequiresUserTest extends BaseRequiresUserTest {
    
    public function setUp() {
        parent::setUp();
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            "HTTP/1.1 200 OK",
        )));
    }
    
}