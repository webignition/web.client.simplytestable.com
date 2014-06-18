<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\TaskType;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

abstract class ServiceTest extends BaseTestCase {


    /**
     * @return \SimplyTestable\WebClientBundle\Services\TaskTypeService
     */
    protected function getTaskTypeService() {
        return $this->container->get('simplytestable.services.tasktypeservice');
    }

}
