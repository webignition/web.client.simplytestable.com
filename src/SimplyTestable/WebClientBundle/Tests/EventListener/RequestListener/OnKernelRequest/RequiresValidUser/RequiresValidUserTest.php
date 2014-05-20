<?php

namespace SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\RequiresValidUser;

use SimplyTestable\WebClientBundle\Tests\EventListener\RequestListener\OnKernelRequest\OnKernelRequestTest;

abstract class RequiresValidUserTest extends OnKernelRequestTest {

    abstract protected function getUserAuthenticateHttpResponse();

    protected function buildEvent() {
        $this->setHttpFixtures($this->buildHttpFixtureSet(array(
            $this->getRemoteTestSummaryHttpResponse(),
            $this->getUserAuthenticateHttpResponse()
        )));

        return parent::buildEvent();
    }


    protected function getControllerActionString() {
        return 'SimplyTestable\WebClientBundle\Controller\View\Test\History\IndexController::indexAction';
    }

    protected function getControllerRouteString() {
        return 'view_user_account_index_index';
    }

    private function getRemoteTestSummaryHttpResponse() {
        return <<<'EOD'
HTTP/1.1 200 OK
Content-Type: application/json

{
  "id": 1,
  "user": "foo",
  "website": "http:\/\/example.com\/",
  "state": "rejected",
  "url_count": 0,
  "task_count": 0,
  "task_count_by_state": {
    "cancelled": 0,
    "queued": 0,
    "in-progress": 0,
    "completed": 0,
    "awaiting-cancellation": 0,
    "queued-for-assignment": 0,
    "failed-no-retry-available": 0,
    "failed-retry-available": 0,
    "failed-retry-limit-reached": 0,
    "skipped": 0
  },
  "task_types": [
    {
      "name": "HTML validation"
    }
  ],
  "errored_task_count": 0,
  "cancelled_task_count": 0,
  "skipped_task_count": 0,
  "warninged_task_count": 0,
  "task_type_options": {
    "CSS validation": {
      "ignore-warnings": "1",
      "vendor-extensions": "warn",
      "ignore-common-cdns": "1"
    },
    "JS static analysis": {
      "ignore-common-cdns": "1",
      "jslint-option-unparam": "0",
      "jslint-option-continue": "0",
      "jslint-option-debug": "0",
      "jslint-option-evil": "0",
      "jslint-option-forin": "0",
      "jslint-option-sub": "0",
      "jslint-option-bitwise": "0",
      "jslint-option-browser": "1",
      "jslint-option-maxerr": "50",
      "jslint-option-indent": "4",
      "jslint-option-maxlen": "256",
      "jslint-option-eqeq": "1",
      "jslint-option-newcap": "1",
      "jslint-option-nomen": "1",
      "jslint-option-plusplus": "1",
      "jslint-option-regexp": "1",
      "jslint-option-sloppy": "1",
      "jslint-option-stupid": "1",
      "jslint-option-vars": "1",
      "jslint-option-white": "1",
      "jslint-option-anon": "1"
    }
  },
  "type": "Single URL",
  "is_public": true,
  "parameters": "",
  "error_count": 0,
  "warning_count": 0,
  "rejection": {
    "reason": "plan-constraint-limit-reached",
    "constraint": {
      "name": "single_url_jobs_per_url",
      "limit": 1,
      "is_available": true
    }
  }
}
EOD;
    }
}