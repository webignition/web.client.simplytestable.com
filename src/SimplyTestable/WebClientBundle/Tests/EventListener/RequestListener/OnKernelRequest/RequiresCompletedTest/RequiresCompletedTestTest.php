<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresCompletedTest;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresCompletedTestTest extends OnKernelRequestTest {

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

        $event->getRequest()->attributes->add(array(
            'website' => self::WEBSITE,
            'test_id' => self::TEST_ID,
        ));

        return $event;
    }

    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\View\Test\Results\IndexController::indexAction';
    }

    protected function getControllerRouteString() {
        return 'view_test_results_index_index';
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