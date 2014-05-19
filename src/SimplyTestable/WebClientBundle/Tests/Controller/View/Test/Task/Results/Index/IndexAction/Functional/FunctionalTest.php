<?php

namespace SimplyTestable\WebClientBundle\Tests\Controller\View\Test\Task\Results\Index\IndexAction\Functional;

use SimplyTestable\WebClientBundle\Tests\Controller\Base\FunctionalTest as BaseFunctionalTest;

abstract class FunctionalTest extends BaseFunctionalTest {

    const WEBSITE = 'http://example.com/abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';

    public function setUp() {
        parent::setUp();
        $this->removeAllTests();
        $this->setHttpFixtures($this->getHttpFixtures($this->getFixturesDataPath()));
        $this->setUser($this->makeUser());
    }

    protected function getRouteParameters() {
        return array(
            'website' => self::WEBSITE,
            'test_id' => 1,
            'task_id' => $this->getTaskId()
        );
    }

    protected function getWebsite() {
        return self::WEBSITE;
    }

    abstract protected function getTaskId();

}