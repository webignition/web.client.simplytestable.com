<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect\TestAction;

use SimplyTestable\WebClientBundle\Tests\Functional\Controller\Redirect\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    protected function setUp() {
        parent::setUp();

        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath()));
    }

    protected function getActionName() {
        return 'testAction';
    }

    /**
     * has website and has test id
     *
     */


}


