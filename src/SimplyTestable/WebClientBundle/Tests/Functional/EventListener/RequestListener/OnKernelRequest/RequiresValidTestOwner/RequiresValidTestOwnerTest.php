<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\RequiresValidTestOwner;

use SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresValidTestOwnerTest extends OnKernelRequestTest {

    const WEBSITE = 'http://example.com/';
    const TEST_ID = 1;

    abstract protected function getRemoteTestSummaryHttpResponse();

    protected function buildEvent() {
        $event = parent::buildEvent();

        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            $this->getRemoteTestSummaryHttpResponse(),
            $this->getPublicUserSummaryHttpResponse(),
            $this->getRemoteTestSummaryHttpResponse(),
        )));

//        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
//            $this->getPublicUserSummaryHttpResponse(),
//            $this->getRemoteTestSummaryHttpResponse(),
//        )));

        $event->getRequest()->attributes->add(array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID
        ));

        return $event;
    }

    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\View\Test\Results\Rejected\IndexController::indexAction';
    }

    protected function getControllerRouteString() {
        return 'view_test_results_preparing_index_index';
    }


    private function getPublicUserSummaryHttpResponse() {
return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{
  "email": "public@simplytestable.com",
  "plan_constraints": {
    "urls_per_job": 10
  },
  "user_plan": {
    "plan": {
      "is_premium": false,
      "name": "public"
    },
    "start_trial_period": 30
  }
}
EOD;
    }

}