<?php

namespace SimplyTestable\WebClientBundle\Tests\Services\Plans\GetList;

use SimplyTestable\WebClientBundle\Tests\Services\Plans\ServiceTest;

class GetListTest extends ServiceTest {

    public function testListIsNotEmpty() {
        $this->assertTrue(count($this->getPlansService()->getList()) > 0);
    }

    public function testListContainsUserPlans() {
        foreach ($this->getPlansService()->getList() as $plan) {
            $this->assertInstanceOf('SimplyTestable\WebClientBundle\Model\User\Plan', $plan);
        }
    }


    public function testGetPremiumOnlyPlans() {
        $this->getPlansService()->listPremiumOnly();
        foreach ($this->getPlansService()->getList() as $plan) {
            $this->assertTrue($plan->getAccountPlan()->getIsPremium());
        }
    }

}
