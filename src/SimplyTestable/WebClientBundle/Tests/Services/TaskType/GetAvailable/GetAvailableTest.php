<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TaskType\GetAvailable;

use SimplyTestable\WebClientBundle\Tests\Services\TaskType\ServiceTest;

abstract class GetAvailableTest extends ServiceTest {

    abstract protected function getExpectedTaskTypeKeys();

    public function testGetAvailable() {
        $this->assertEquals($this->getExpectedTaskTypeKeys(), array_keys($this->getTaskTypeService()->getAvailable()));
    }


}
