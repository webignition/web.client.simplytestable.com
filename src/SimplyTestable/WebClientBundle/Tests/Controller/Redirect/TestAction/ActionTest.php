<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\Redirect\TestAction;

use SimplyTestable\WebClientBundle\Tests\Controller\Redirect\ActionTest as BaseActionTest;

abstract class ActionTest extends BaseActionTest {

    public function setUp() {
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


