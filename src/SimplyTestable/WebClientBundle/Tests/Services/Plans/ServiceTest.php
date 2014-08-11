<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\Plans;

use SimplyTestable\WebClientBundle\Tests\BaseTestCase;

abstract class ServiceTest extends BaseTestCase {


    /**
     *
     * @return \SimplyTestable\WebClientBundle\Services\PlansService
     */
    protected function getPlansService() {
        return $this->container->get('simplytestable.services.plansService');
    }

}
