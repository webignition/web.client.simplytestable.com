<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\PrivateUser;

use SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Results\Index\IndexAction\Functional\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    public function setUp() {
        parent::setUp();
        $this->setUser($this->makeUser());
    }

    protected function getRequestQueryData() {
        return array(
            'filter' => 'with-errors'
        );
    }


}