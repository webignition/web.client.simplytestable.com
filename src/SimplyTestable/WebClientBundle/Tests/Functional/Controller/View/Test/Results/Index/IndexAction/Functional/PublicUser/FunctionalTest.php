<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\PublicUser;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\View\Test\Results\Index\IndexAction\Functional\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    protected function setUp() {
        parent::setUp();
        $this->setUser($this->getUserService()->getPublicUser());
    }

    protected function getRequestQueryData() {
        return array(
            'filter' => 'without-errors'
        );
    }


}